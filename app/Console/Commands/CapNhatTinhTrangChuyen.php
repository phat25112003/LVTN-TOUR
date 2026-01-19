<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChuyenTour;
use Carbon\Carbon;
use App\Services\ChuyenTourStatusService;

class CapNhatTinhTrangChuyen extends Command
{
    protected $signature = 'chuyen:cap-nhat-trang-thai';

    protected $description = 'Tự động cập nhật trạng thái chuyến tour';

    public function handle()
    {
        ChuyenTourStatusService::capNhatTatCa();

        $this->info('Đã cập nhật trạng thái chuyến tour.');
    }
}
