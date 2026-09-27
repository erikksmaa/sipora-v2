<?php

return [
    'enabled' => (bool) env('RECAPTCHA_ENABLED', true),
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    // Empty means connect to Google directly instead of inheriting ambient proxy variables.
    'http_proxy' => env('RECAPTCHA_HTTP_PROXY', ''),
    'min_score' => (float) env('RECAPTCHA_MIN_SCORE', 0.5),
    'hostname' => env('RECAPTCHA_HOSTNAME', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
    'hostnames' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('RECAPTCHA_HOSTNAMES', ''))
    ))),
    'actions' => [
        'register' => env('RECAPTCHA_ACTION_REGISTER', 'register'),
        'login' => env('RECAPTCHA_ACTION_LOGIN', 'login'),
        'forgot_password' => env('RECAPTCHA_ACTION_FORGOT_PASSWORD', 'forgot_password'),
    ],
];
