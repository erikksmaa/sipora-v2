<?php

return [
    // Persist dates in UTC; convert only when presenting them to users.
    'display_timezone' => 'Asia/Jakarta',
    'contact' => [
        'organization' => 'Dindikpora Kabupaten Pemalang',
        'address' => env('SIPORA_CONTACT_ADDRESS'),
        'email' => env('SIPORA_CONTACT_EMAIL'),
        'phone' => env('SIPORA_CONTACT_PHONE'),
        'website' => env('SIPORA_CONTACT_WEBSITE'),
        'service_hours' => env('SIPORA_CONTACT_SERVICE_HOURS'),
    ],
];
