<?php

return [
    'defaults' => [
        'guard' => 'web', 
        'passwords' => 'users',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'quantri',
        ],
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'quantri' => [
            'driver' => 'eloquent',
            'model' => App\Models\QuanTri::class,
        ],
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\NguoiDung::class,
        ],
    ],

    'passwords' => [
        'quantri' => [
            'provider' => 'quantri',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];