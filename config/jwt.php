<?php

return [
    'private_key' => env('JWT_PRIVATE_KEY_PATH')
        ? (str_starts_with(env('JWT_PRIVATE_KEY_PATH'), '/') ? env('JWT_PRIVATE_KEY_PATH') : base_path(env('JWT_PRIVATE_KEY_PATH')))
        : storage_path('jwt/private.pem'),
    'public_key' => env('JWT_PRIVATE_KEY_PATH')
        ? (str_starts_with(env('JWT_PUBLIC_KEY_PATH'), '/') ? env('JWT_PUBLIC_KEY_PATH') : base_path(env('JWT_PUBLIC_KEY_PATH')))
        : storage_path('jwt/public.pem'),
    'algo' => 'RS256',
    'app_url' => env('APP_URL'),
    'app_slug' => env('APP_SLUG'),
    'leeway' => 0,
];
