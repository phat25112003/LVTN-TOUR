<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\ExpireDatCho;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('expire-dat-cho')
    ->everyMinute();

// 🔄 Cập nhật trạng thái chuyến tour
Schedule::command('chuyen:cap-nhat-trang-thai')
    ->dailyAt('00:01');