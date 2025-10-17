<?php

return [
    'defaults' => [
        'guard' => 'admin',
        'passwords' => 'quantri',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'quantri',
        ],
    ],

    'providers' => [
        'quantri' => [
            'driver' => 'eloquent',
            'model' => App\Models\QuanTri::class,
        ],
    ],

    'passwords' => [
        'quantri' => [
            'provider' => 'quantri',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];