<?php

namespace App\Services;

use App\Models\User;
use App\Policies\InternalUserPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class InternalAccountService
{
    public function __construct(private readonly InternalUserPolicy $policy) {}

    public function query(): Builder
    {
        return User::whereHas('roles', fn ($roles) => $roles->whereIn('name', ['admin', 'verifier']))
            ->whereDoesntHave('roles', fn ($roles) => $roles->where('name', 'youth'));
    }

    public function create(User $actor, array $data): array
    {
        abort_unless($this->policy->viewAny($actor), 403);
        $role = $data['role'];
        abort_unless(in_array($role, ['admin', 'verifier'], true), 422);
        if ($role === 'admin') {
            $this->confirmPassword($actor, $data['admin_password'] ?? '');
        }
        $temporary = $this->temporaryPassword();
        $user = DB::transaction(function () use ($actor, $data, $role, $temporary) {
            $this->lockAdministrators();
            $user = User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => $temporary]);
            $user->forceFill(['internal_active' => true, 'must_change_password' => true])->save();
            $user->assignRole($role);
            $this->audit($actor, $user, 'internal_account_created', ['role' => $role]);
            $this->audit($actor, $user, 'internal_role_assigned', ['role' => $role]);

            return $user;
        }, 3);

        return ['user' => $user, 'temporaryPassword' => $temporary];
    }

    public function edit(User $actor, User $target, array $data): User
    {
        return DB::transaction(function () use ($actor, $target, $data) {
            $this->lockAdministrators();
            $target = User::whereKey($target->getKey())->lockForUpdate()->firstOrFail();
            $this->authorizeManage($actor, $target, $data['admin_password'] ?? '');
            $changed = [];
            if ($target->name !== $data['name']) {
                $target->name = $data['name'];
                $changed[] = 'name';
            }
            if ($target->email !== $data['email']) {
                Password::deleteToken($target);
                $target->email = $data['email'];
                $target->email_verified_at = null;
                $this->bumpSessions($target);
                $changed[] = 'email';
            }
            $target->save();
            $this->audit($actor, $target, 'internal_account_edited', ['fields' => $changed]);

            return $target;
        }, 3);
    }

    public function setActive(User $actor, User $target, bool $active, string $confirmation = ''): void
    {
        DB::transaction(function () use ($actor, $target, $active, $confirmation) {
            $this->lockAdministrators();
            $target = User::whereKey($target->getKey())->lockForUpdate()->firstOrFail();
            $this->authorizeManage($actor, $target, $confirmation);
            if (! $active && $target->hasRole('admin') && $target->internal_active && $this->query()->role('admin')->where('internal_active', true)->count() <= 1) {
                throw ValidationException::withMessages(['active' => 'Administrator aktif terakhir tidak dapat dinonaktifkan.']);
            }
            if ($target->internal_active === $active) {
                return;
            }
            $target->internal_active = $active;
            $this->bumpSessions($target);
            $target->save();
            $this->audit($actor, $target, $active ? 'internal_account_activated' : 'internal_account_deactivated');
        }, 3);
    }

    public function temporaryReset(User $actor, User $target, string $confirmation = ''): array
    {
        $temporary = $this->temporaryPassword();
        $user = DB::transaction(function () use ($actor, $target, $confirmation, $temporary) {
            $this->lockAdministrators();
            $target = User::whereKey($target->getKey())->lockForUpdate()->firstOrFail();
            $this->authorizeManage($actor, $target, $confirmation);
            $target->password = $temporary;
            $target->must_change_password = true;
            $this->bumpSessions($target);
            $target->save();
            Password::deleteToken($target);
            $this->audit($actor, $target, 'internal_temporary_password_reset');

            return $target;
        }, 3);

        return ['user' => $user, 'temporaryPassword' => $temporary];
    }

    public function sendResetLink(User $actor, User $target, string $confirmation = ''): void
    {
        $this->authorizeManage($actor, $target, $confirmation);
        try {
            $status = Password::sendResetLink(['email' => $target->email]);
        } catch (TransportExceptionInterface $exception) {
            throw ValidationException::withMessages(['email' => 'Tautan reset belum dapat dikirim. Periksa konfigurasi pengiriman email.']);
        }
        if ($status !== Password::ResetLinkSent) {
            throw ValidationException::withMessages(['email' => $status === Password::ResetThrottled ? 'Tunggu satu menit sebelum meminta tautan baru.' : 'Tautan reset belum dapat dikirim.']);
        }
        $this->audit($actor, $target, 'internal_reset_link_requested');
    }

    public function resendVerification(User $actor, User $target, string $confirmation = ''): void
    {
        $this->authorizeManage($actor, $target, $confirmation);
        if ($target->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'Email akun ini sudah terverifikasi.']);
        }
        try {
            $target->sendEmailVerificationNotification();
        } catch (TransportExceptionInterface $exception) {
            throw ValidationException::withMessages(['email' => 'Email verifikasi belum dapat dikirim. Periksa konfigurasi email.']);
        }
        $this->audit($actor, $target, 'internal_verification_resent');
    }

    public function changePassword(User $user, #[\SensitiveParameter] string $password, string $event = 'internal_password_changed'): User
    {
        return DB::transaction(function () use ($user, $password, $event) {
            $target = User::whereKey($user->getKey())->lockForUpdate()->firstOrFail();
            abort_unless($target->hasAnyRole(['admin', 'verifier']), 403);
            if (Hash::check($password, $target->password ?? '')) {
                throw ValidationException::withMessages(['password' => 'Gunakan kata sandi baru yang berbeda dari kata sandi sebelumnya.']);
            }
            $forced = $target->must_change_password;
            $target->password = $password;
            $target->must_change_password = false;
            $this->bumpSessions($target);
            $target->save();
            Password::deleteToken($target);
            $this->audit($target, $target, $forced ? 'internal_forced_password_changed' : $event);

            return $target;
        }, 3);
    }

    private function authorizeManage(User $actor, User $target, #[\SensitiveParameter] string $confirmation): void
    {
        abort_unless($this->policy->manage($actor, $target), 403);
        if ($target->hasRole('admin')) {
            $this->confirmPassword($actor, $confirmation);
        }
    }

    private function confirmPassword(User $actor, #[\SensitiveParameter] string $password): void
    {
        if (! Hash::check($password, $actor->password ?? '')) {
            throw ValidationException::withMessages(['admin_password' => 'Konfirmasi kata sandi Administrator saat ini diperlukan.']);
        }
    }

    private function lockAdministrators(): void
    {
        Role::where('name', 'admin')->where('guard_name', 'web')->lockForUpdate()->firstOrFail();
    }

    private function temporaryPassword(): string
    {
        do {
            $password = Str::password(16);
        } while (! preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9])/s', $password));

        return $password;
    }

    private function bumpSessions(User $target): void
    {
        $target->internal_session_version++;
        $target->remember_token = Str::random(60);
        if (in_array(config('session.driver'), ['database', 'binary-database'], true)) {
            DB::connection(config('session.connection'))->table(config('session.table'))->where('user_id', $target->getKey())->delete();
        }
    }

    private function audit(User $actor, User $target, string $event, array $properties = []): void
    {
        activity('internal_accounts')->causedBy($actor)->performedOn($target)->event($event)->withProperties($properties)->log(str_replace('_', ' ', $event));
    }
}
