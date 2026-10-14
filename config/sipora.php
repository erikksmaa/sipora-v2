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
        // Location supplied by the SIPORA project owner; no guessed street address.
        'maps_embed_url' => env('SIPORA_CONTACT_MAPS_EMBED_URL', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.9255939869213!2d109.3862443!3d-6.8995023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fdb29beb216a7%3A0x1b5757c95adadb02!2sDinas%20Pendidikan%20dan%20Kebudayaan%20Kabupaten%20Pemalang!5e0!3m2!1sid!2sid!4v1791900023118!5m2!1sid!2sid'),
        'maps_url' => env('SIPORA_CONTACT_MAPS_URL', 'https://www.google.com/maps?cid=1970139884322216706'),
    ],
];
