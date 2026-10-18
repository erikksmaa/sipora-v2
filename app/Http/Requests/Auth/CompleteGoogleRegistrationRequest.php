<?php

namespace App\Http\Requests\Auth;

use App\Services\Youth\GoogleOnboardingService;
use App\Services\Youth\RegistrationWizard;
use App\Services\Youth\YouthRegistrationRules;
use Illuminate\Foundation\Http\FormRequest;

final class CompleteGoogleRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth')
            && ! $this->user()->isInternalAccount()
            && $this->user()->socialAccounts()->where('provider', 'google')->exists();
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:160']]
            + app(YouthRegistrationRules::class)->rules($this->input('administrative_area_id'), $this->user());
    }

    protected function passedValidation(): void
    {
        if (app(GoogleOnboardingService::class)->pending($this->user())) {
            app(RegistrationWizard::class)->requireComplete($this);
        }
    }

    public function messages(): array
    {
        return (new AuthRequest)->messages();
    }
}
