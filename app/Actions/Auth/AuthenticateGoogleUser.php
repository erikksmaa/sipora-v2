<?php

namespace App\Actions\Auth;

use App\Enums\SystemRole;
use App\Models\User;
use App\Models\UserSocialAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as ProviderUser;

class AuthenticateGoogleUser
{
    public function execute(ProviderUser $identity): User
    {
        $email = mb_strtolower((string) $identity->getEmail());
        $claims = $identity->getRaw();
        $verified = ($claims['email_verified'] ?? $claims['verified_email'] ?? false) === true;
        if (! $verified || ! filter_var($email, FILTER_VALIDATE_EMAIL) || ! $identity->getId()) {
            $this->deny();
        }

        return DB::transaction(function () use ($identity, $email, $claims): User {
            $account = UserSocialAccount::where('provider', 'google')
                ->where('provider_user_id', $identity->getId())->lockForUpdate()->first();
            if ($account) {
                return $account->user;
            }

            // Google is authoritative for Gmail or the verified Workspace domain.
            // Third-party email ownership may have changed since Google verified it.
            $domain = substr(strrchr($email, '@'), 1);
            $authoritative = $domain === 'gmail.com'
                || (is_string($claims['hd'] ?? null) && strtolower($claims['hd']) === $domain);
            if (! $authoritative) {
                $this->deny();
            }

            $user = User::where('email', $email)->lockForUpdate()->first();
            if ($user) {
                if (! $user->hasVerifiedEmail()
                    || $user->hasAnyRole([SystemRole::Admin, SystemRole::Verifier])
                    || $user->socialAccounts()->where('provider', 'google')->exists()) {
                    $this->deny();
                }
            } else {
                $user = User::create([
                    'name' => mb_substr($identity->getName() ?: 'SIPORA Youth', 0, 255),
                    'email' => $email,
                    'password' => null,
                ]);
                $user->markEmailAsVerified();
                $user->assignRole(SystemRole::Youth);
            }

            $user->socialAccounts()->create([
                'provider' => 'google',
                'provider_user_id' => $identity->getId(),
                'provider_email' => $email,
                'avatar_url' => null,
            ]);

            return $user;
        });
    }

    private function deny(): never
    {
        throw ValidationException::withMessages([
            'email' => 'This Google account cannot be safely linked. Use email sign-in or account recovery.',
        ]);
    }
}
