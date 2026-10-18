<?php

namespace App\Services\Youth;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class RegistrationWizard
{
    private const FIELDS = [
        ['name', 'national_id', 'birth_place', 'birth_date', 'gender', 'email', 'password', 'password_confirmation'],
        ['administrative_area_id', 'village_id', 'address_line', 'rt', 'rw', 'postal_code'],
        ['interests'],
    ];

    public function fields(int $step): array
    {
        return self::FIELDS[$step];
    }

    private function key(Request $request): string
    {
        return 'registration_wizard.'.($request->user() ? $request->user()->uuid() : 'manual');
    }

    private function fingerprint(Request $request, int $step): string
    {
        $values = [];
        foreach ($this->fields($step) as $field) {
            if ($request->user() && in_array($field, ['email', 'password', 'password_confirmation'])) {
                continue;
            }
            $value = $request->input($field);
            $values[$field] = $field === 'email' && is_string($value) ? mb_strtolower(trim($value)) : $value;
        }

        // Only keyed fingerprints enter the session; never NIK/password/draft data.
        return hash_hmac('sha256', json_encode($values, JSON_THROW_ON_ERROR), config('app.key'));
    }

    public function requirePrevious(Request $request, int $step): void
    {
        $proofs = $request->session()->get($this->key($request), []);
        for ($index = 0; $index < $step; $index++) {
            if (! isset($proofs[$index]) || ! hash_equals($proofs[$index], $this->fingerprint($request, $index))) {
                throw ValidationException::withMessages(['wizard' => 'Selesaikan langkah pendaftaran secara berurutan melalui tombol Selanjutnya.']);
            }
        }
    }

    public function accept(Request $request, int $step): void
    {
        $proofs = array_slice($request->session()->get($this->key($request), []), 0, $step);
        $proofs[$step] = $this->fingerprint($request, $step);
        $request->session()->put($this->key($request), $proofs);
    }

    public function requireComplete(Request $request): void
    {
        $this->requirePrevious($request, 3);
    }
}
