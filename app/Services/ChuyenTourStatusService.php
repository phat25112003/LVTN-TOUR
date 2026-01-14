<?php

namespace App\Services;

use App\Models\ChuyenTour;
use Carbon\Carbon;

class ChuyenTourStatusService
{
    public static function capNhatTrangThaiTuDong(): void
    {
        $today = Carbon::today();
        $now   = Carbon::now();

        $chuyens = ChuyenTour::whereNotIn('tinhTrangChuyen', [
            'Huy',
            'DaKhoiHanh'
        ])->get();

        foreach ($chuyens as $chuyen) {

            $ngayBatDau = Carbon::parse($chuyen->ngayBatDau);
            $thoiDiemDongBan = self::thoiDiemDongBan($chuyen);

            // ❌ HẾT HẠN BÁN – CHƯA ĐỦ KHÁCH → HUỶ
            if ($now->gte($thoiDiemDongBan) && $chuyen->tinhTrangChuyen === 'ChuaDuKhach') {
                $chuyen->update(['tinhTrangChuyen' => 'Huy']);
                continue;
            }

            // 🚍 ĐẾN NGÀY KHỞI HÀNH – ĐỦ KHÁCH → KHỞI HÀNH
            if ($ngayBatDau->equalTo($today) && $chuyen->tinhTrangChuyen === 'DuKhach') {
                $chuyen->update(['tinhTrangChuyen' => 'DaKhoiHanh']);
                continue;
            }

            // ❌ PHÒNG NGỪA: ĐẾN NGÀY KHỞI HÀNH MÀ CHƯA ĐỦ KHÁCH
            if ($ngayBatDau->equalTo($today) && $chuyen->tinhTrangChuyen === 'ChuaDuKhach') {
                $chuyen->update(['tinhTrangChuyen' => 'Huy']);
            }
        }
    }

    public static function thoiDiemDongBan(ChuyenTour $chuyen)
    {
        return \Carbon\Carbon::parse($chuyen->ngayBatDau)
            ->subDay()
            ->startOfDay();
    }
}
