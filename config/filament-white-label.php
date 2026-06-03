<?php

return [

    'enabled' => env('FILAMENT_WHITE_LABEL_ENABLED', true),

    'cache_ttl' => env('FILAMENT_WHITE_LABEL_CACHE_TTL', 300),

    'disk' => env('FILAMENT_WHITE_LABEL_DISK', 'public'),

    'storage_path_prefix' => 'brand',

    'defaults' => [
        'brand_name' => env('APP_NAME', 'Filament'),
        'logo' => null,
        'favicon' => null,
        'font_family' => 'Inter',
        'colors' => [
            'primary' => '#3b82f6',
            'secondary' => '#64748b',
            'danger' => '#ef4444',
            'warning' => '#f59e0b',
            'success' => '#22c55e',
            'info' => '#3b82f6',
        ],
        'email_from_address' => env('MAIL_FROM_ADDRESS'),
        'email_from_name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Filament')),
    ],

    'security' => [
        'disable_custom_css' => env('FILAMENT_WHITE_LABEL_DISABLE_CSS', false),
        'max_css_length' => 50000,
    ],

    'ui' => [
        'show_preview' => env('FILAMENT_WHITE_LABEL_PREVIEW', false),
        'navigation_group' => 'Settings',
        'navigation_sort' => 10,
    ],

    'email' => [
        'enabled' => env('FILAMENT_WHITE_LABEL_EMAIL_ENABLED', true),
    ],

    'login' => [
        'enabled' => env('FILAMENT_WHITE_LABEL_LOGIN_ENABLED', true),
    ],

    'fonts' => [
        'enabled' => true,
        'api_key' => env('GOOGLE_FONTS_API_KEY'),
    ],
];