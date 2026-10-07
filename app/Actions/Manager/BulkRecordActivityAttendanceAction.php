<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityParticipation;
use App\Models\ActivitySession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class BulkRecordActivityAttendanceAction
{
    public function execute(User $manager, Activity $activity, ActivitySession $session, array $entries): void
    {
        DB::transaction(function () use ($manager, $activity, $session, $entries): void {
            $session = ActivitySession::query()->whereKey($session->getKey())->lockForUpdate()->firstOrFail();
            if ($session->activity_id !== $activity->getKey()) {
                throw ValidationException::withMessages(['attendance' => 'Sesi harus berasal dari Activity ini.']);
            }

            $participants = ActivityParticipation::query()
                ->where('activity_id', $activity->getKey())
                ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
                ->lockForUpdate()->get();
            $expectedIds = $participants->map(fn (ActivityParticipation $participant) => $participant->uuid())->sort()->values()->all();
            $submittedIds = array_keys($entries);
            sort($submittedIds);
            if ($submittedIds !== $expectedIds) {
                throw ValidationException::withMessages(['attendance' => 'Daftar peserta berubah atau memuat peserta yang bukan peserta diterima Activity ini. Muat ulang halaman lalu coba lagi.']);
            }

            $existing = ActivityAttendance::withTrashed()
                ->where('activity_session_id', $session->getKey())
                ->whereIn('participation_id', $participants->modelKeys())
                ->lockForUpdate()->get()->keyBy('participation_id');

            foreach ($participants as $participant) {
                $entry = $entries[$participant->uuid()];
                $attendance = $existing->get($participant->getKey());
                $event = $attendance && ! $attendance->trashed() ? 'attendance_updated' : 'attendance_marked';
                $attendance ??= new ActivityAttendance([
                    'activity_session_id' => $session->getKey(),
                    'participation_id' => $participant->getKey(),
                ]);
                $attendance->attendance_status = $entry['status'];
                $attendance->notes = $entry['notes'] ?? null;
                $attendance->recorded_by = $manager->getKey();
                if ($entry['status'] !== ActivityAttendance::STATUS_PRESENT) {
                    $attendance->checked_in_at = null;
                    $attendance->checked_out_at = null;
                }
                if ($attendance->trashed()) {
                    $attendance->deleted_at = null;
                }
                if (! $attendance->exists || $attendance->isDirty()) {
                    $attendance->save();
                    activity()->causedBy($manager)->performedOn($attendance)->event($event)
                        ->withProperties([
                            'activity_id' => $activity->uuid(),
                            'session_id' => $session->uuid(),
                            'participation_id' => $participant->uuid(),
                            'attendance_status' => $attendance->attendance_status,
                        ])->log('Presensi sesi Activity diperbarui');
                }
            }
        });
    }
}
