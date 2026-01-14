<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\DanhMuc;
use App\Models\LoaiDuLich;
use App\Models\ThanhToan;
use App\Observers\ThanhToanObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layout.header', function ($view) {
            $view->with([
                'danhmucs' => DanhMuc::all(),
                'loaidulichs' => LoaiDuLich::all()
            ]);
        });
    }
}
