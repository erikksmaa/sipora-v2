<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailRequest extends EmailVerificationRequest
{
    public function authorize(): bool
    {
        return hash_equals($this->user()->uuid(), (string) $this->route('id'))
            && hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'));
    }
}
