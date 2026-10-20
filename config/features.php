<?php

return [
    'google_oauth' => (bool) env('FEATURE_GOOGLE_OAUTH', false),
    'recaptcha' => (bool) env('FEATURE_RECAPTCHA', false),
];
