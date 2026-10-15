<?php

namespace App\Services\Program;

use App\Models\Program;
use App\Models\ProgramApplication;
use App\Models\User;
use App\Notifications\ProgramApplicationChanged;
use App\Services\Discovery\ProgramDiscoveryService;
use App\Services\Youth\YouthOnboardingService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class ProgramIntakeService
{
    public function __construct(private readonly ProgramDiscoveryService $discovery, private readonly YouthOnboardingService $onboarding) {}

    public function summary(Program $program): array
    {
        $counts = $program->applications()->selectRaw('status, COUNT(*) AS total')->groupBy('status')->pluck('total', 'status')->map(fn ($n) => (int) $n)->all();
        $counts = array_replace(array_fill_keys(array_keys(ProgramApplication::LABELS), 0), $counts);

        return ['counts' => $counts, 'total' => array_sum($counts), 'quota' => $program->quota,
            'remaining' => $program->quota === null ? null : max(0, $program->quota - $counts['accepted'])];
    }

    public function blockingReason(?User $user, Program $program): ?string
    {
        if (! $program->registration_enabled) {
            return 'Pendaftaran Program tidak diaktifkan.';
        }
        if ($program->execution_status !== Program::STATUS_RUNNING) {
            return 'Program tidak sedang menerima pendaftaran.';
        }
        if (! $this->discovery->isPublic($program)) {
            return 'Program belum tersedia untuk pendaftaran publik.';
        }
        if ($program->registration_open_at && now()->lt($program->registration_open_at)) {
            return 'Pendaftaran belum dibuka.';
        }
        if ($program->registration_close_at && now()->gt($program->registration_close_at)) {
            return 'Pendaftaran telah ditutup.';
        }
        if (! $user) {
            return 'Masuk sebagai pemuda untuk mendaftar.';
        }
        if (! $user->hasRole('youth')) {
            return 'Pendaftaran hanya tersedia untuk akun pemuda.';
        }
        $state = $this->onboarding->state($user);
        if (! $state['onboardingComplete']) {
            return $this->onboarding->lockedReason($state);
        }
        if ($program->applications()->where('user_id', $user->getKey())->exists()) {
            return 'Anda sudah memiliki riwayat pendaftaran pada Program ini.';
        }
        if ($program->selection_mode === 'open' && $program->quota !== null && $program->participants()->count() >= $program->quota) {
            return 'Kuota Program telah terpenuhi.';
        }

        return null;
    }

    public function configure(User $actor, Program $program, array $data): Program
    {
        $data = Validator::make($data, ProgramIntakeRules::rules())->validate();

        return DB::transaction(function () use ($actor, $program, $data) {
            $program = Program::whereKey($program->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($actor)->authorize('configureIntake', $program);
            foreach (['registration_open_at', 'registration_close_at'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = filled($data[$field]) ? Carbon::parse($data[$field], config('sipora.display_timezone'))->utc() : null;
                }
            }
            Validator::make(array_merge($program->only(array_keys(ProgramIntakeRules::rules())), $data), ProgramIntakeRules::rules())->validate();
            if (isset($data['selection_mode']) && $data['selection_mode'] !== $program->selection_mode && $program->applications()->exists()) {
                $this->fail('selection_mode', 'Mode seleksi tidak dapat diubah setelah ada pendaftaran.');
            }
            if (isset($data['quota']) && $data['quota'] < $program->participants()->count()) {
                $this->fail('quota', 'Kuota tidak boleh lebih kecil dari jumlah peserta yang sudah diterima.');
            }
            $program->fill($data)->save();
            activity()->causedBy($actor)->performedOn($program)->event('program_intake_configured')->log('Pengaturan pendaftaran Program diperbarui');

            return $program->fresh();
        }, 3);
    }

    public function apply(User $user, Program $program): ProgramApplication
    {
        return DB::transaction(function () use ($user, $program) {
            $program = Program::whereKey($program->getKey())->lockForUpdate()->firstOrFail();
            if ($reason = $this->blockingReason($user, $program)) {
                $this->fail('registration', $reason);
            }
            $application = $program->applications()->create(['user_id' => $user->getKey(), 'status' => $program->selection_mode === 'open' ? 'accepted' : 'submitted']);
            $this->record($user, $application, 'program_application_created');
            $user->notify(new ProgramApplicationChanged($application));

            return $application;
        }, 3);
    }

    public function review(User $actor, Program $program, ProgramApplication $application, string $decision, string $expectedStatus, ?string $notes): ProgramApplication
    {
        return DB::transaction(function () use ($actor, $program, $application, $decision, $expectedStatus, $notes) {
            $program = Program::whereKey($program->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($actor)->authorize('manageApplications', $program);
            $application = $program->applications()->whereKey($application->getKey())->lockForUpdate()->firstOrFail();
            if ($program->execution_status !== Program::STATUS_RUNNING) {
                $this->fail('decision', 'Seleksi hanya dapat dikelola selama Program berjalan.');
            }
            if ($application->status !== $expectedStatus) {
                $this->fail('decision', 'Status telah berubah. Muat ulang halaman sebelum mengambil keputusan.');
            }
            $transitions = ['submitted' => ['under_review', 'accepted', 'waitlisted', 'rejected'], 'under_review' => ['accepted', 'waitlisted', 'rejected'], 'waitlisted' => ['under_review', 'accepted', 'rejected']];
            if (! in_array($decision, $transitions[$application->status] ?? [], true)) {
                $this->fail('decision', 'Perubahan status ini tidak diizinkan.');
            }
            if ($decision === 'accepted' && $program->quota !== null && $program->participants()->count() >= $program->quota) {
                $this->fail('decision', 'Kuota Program telah terpenuhi.');
            }
            $application->fill(['status' => $decision, 'review_notes' => filled($notes) ? mb_substr(trim($notes), 0, 2000) : null, 'reviewed_by' => $actor->getKey(), 'reviewed_at' => now()])->save();
            $this->record($actor, $application, 'program_application_reviewed');
            if (in_array($decision, ['accepted', 'waitlisted', 'rejected'], true)) {
                $application->user->notify(new ProgramApplicationChanged($application));
            }

            return $application;
        }, 3);
    }

    public function withdraw(User $user, Program $program): void
    {
        DB::transaction(function () use ($user, $program) {
            $program = Program::whereKey($program->getKey())->lockForUpdate()->firstOrFail();
            $application = $program->applications()->where('user_id', $user->getKey())->lockForUpdate()->firstOrFail();
            if (! in_array($program->execution_status, ['planned', 'running'], true) || ! in_array($application->status, ['submitted', 'under_review', 'waitlisted', 'accepted'], true)) {
                $this->fail('registration', 'Pendaftaran ini tidak dapat ditarik kembali.');
            }
            $application->update(['status' => 'withdrawn']);
            $this->record($user, $application, 'program_application_withdrawn');
        }, 3);
    }

    private function record(User $actor, ProgramApplication $application, string $event): void
    {
        activity()->causedBy($actor)->performedOn($application)->event($event)
            ->withProperties(['program_id' => $application->program->uuid(), 'status' => $application->status])->log('Status pendaftaran Program diperbarui');
    }

    private function fail(string $field, string $message): never
    {
        throw ValidationException::withMessages([$field => $message]);
    }
}
