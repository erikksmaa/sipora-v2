<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

final class ValidateScalarQueryParameters
{
    public function handle(Request $request, Closure $next): Response
    {
        // These filters are scalar throughout SIPORA. Reject q[]=... before
        // controllers cast it to text or pass it to a typed discovery service.
        $keys = ['q', 'search', 'category', 'location', 'area', 'mode', 'registration',
            'availability', 'sort', 'status', 'tab', 'district', 'age', 'year',
            'per_page', 'page', 'reports_page', 'rooms_page'];
        $validator = Validator::make($request->query(), array_fill_keys($keys, ['nullable', 'string']));
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                throw new ValidationException($validator);
            }
            return response()->view('errors.invalid-filters', ['resetUrl' => $request->url()], 422);
        }

        return $next($request);
    }
}
