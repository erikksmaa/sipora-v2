<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\Auth\CompleteGoogleRegistrationRequest;
use App\Services\Youth\RegistrationWizard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class RegistrationStepController extends Controller
{
    public function __invoke(Request $request, int $step, RegistrationWizard $wizard)
    {
        abort_unless($step >= 0 && $step <= 2, 404);
        if ($request->user()) {
            abort_unless($request->user()->hasRole('youth') && ! $request->user()->isInternalAccount()
                && $request->user()->hasVerifiedEmail()
                && $request->user()->socialAccounts()->where('provider', 'google')->exists(), 403);
        }
        $wizard->requirePrevious($request, $step);
        $rulesRequest = $request->user() ? CompleteGoogleRegistrationRequest::createFrom($request) : AuthRequest::createFrom($request);
        $rules = $request->user() ? $rulesRequest->rules() : $rulesRequest->registrationRules();
        $rules = array_filter($rules, fn ($key) => in_array(explode('.', $key)[0], $wizard->fields($step)), ARRAY_FILTER_USE_KEY);
        $data = $request->all();
        if (is_string($data['email'] ?? null)) {
            $data['email'] = mb_strtolower(trim($data['email']));
        }
        Validator::make($data, $rules, $rulesRequest->messages())->validate();
        $wizard->accept($request, $step);

        return response()->json(['next_step' => $step + 1]);
    }
}
