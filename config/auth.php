<?php

return [
    'defaults' => [
        'guard' => 'api', // Keep as fallback (you'll specify guards explicitly)
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'students', // Default provider (can be students or companies)
        ],

        'student' => [
            'driver' => 'jwt',
            'provider' => 'students',
        ],

        'company' => [
            'driver' => 'jwt',
            'provider' => 'companies',
        ],
    ],

    'providers' => [
        'students' => [
            'driver' => 'eloquent',
            'model' => App\Models\Student::class,
        ],
        'companies' => [
            'driver' => 'eloquent',
            'model' => App\Models\Company::class,
        ],
    ],

    'passwords' => [
        'students' => [
            'provider' => 'students',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'companies' => [
            'provider' => 'companies',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
