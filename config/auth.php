<?php

return [
    'defaults' => [
        'guard' => 'web', 
        'passwords' => 'nguoidung',
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'quantri',
        ],
        'web' => [
            'driver' => 'session',
            'provider' => 'nguoidung',
        ],
    ],

    'providers' => [
        'quantri' => [
            'driver' => 'eloquent',
            'model' => App\Models\QuanTri::class,
        ],
        'nguoidung' => [
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
        'nguoidung' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];