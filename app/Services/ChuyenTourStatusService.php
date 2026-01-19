<?php

namespace App\Services;

use App\Models\ChuyenTour;
use Carbon\Carbon;

class ChuyenTourStatusService
{
    /**
     * Cập nhật trạng thái cho MỘT chuyến
     */
    public static function capNhatChoMotChuyen(ChuyenTour $chuyen): void
    {
        $now = Carbon::now(config('app.timezone'));
        $today = Carbon::today(config('app.timezone'));

        $dongBan = self::thoiDiemDongBan($chuyen);
        $ngayBatDau = Carbon::parse($chuyen->ngayBatDau);

        // 1️⃣ ĐỦ CHỖ → DU KHÁCH
        if (
            $chuyen->so_khach_toi_thieu !== null &&
            $chuyen->soLuongDaDat >= $chuyen->so_khach_toi_thieu &&
            !in_array($chuyen->tinhTrangChuyen, ['DuKhach', 'Huy', 'DaKhoiHanh'])
        ) {
            $chuyen->update(['tinhTrangChuyen' => 'DuKhach']);
            return;
        }

        // 2️⃣ HẾT HẠN BÁN – CHƯA ĐỦ → HỦY
        if ($now->gte($dongBan) && $chuyen->tinhTrangChuyen === 'ChuaDuKhach') {
            $chuyen->update(['tinhTrangChuyen' => 'Huy']);
            return;
        }

        // 3️⃣ ĐẾN NGÀY ĐI – ĐỦ → KHỞI HÀNH
        if ($ngayBatDau->isSameDay($today) && $chuyen->tinhTrangChuyen === 'DuKhach') {
            $chuyen->update(['tinhTrangChuyen' => 'DaKhoiHanh']);
        }
    }

    /**
     * 🧹 Cập nhật TẤT CẢ chuyến (dùng cho cron)
     */
    public static function capNhatTatCa(): void
    {
        ChuyenTour::whereNotIn('tinhTrangChuyen', ['Huy', 'DaKhoiHanh'])
            ->get()
            ->each(fn ($chuyen) => self::capNhatChoMotChuyen($chuyen));
    }

    /**
     * ⏰ Thời điểm đóng bán: 23:59 hôm trước ngày đi
     */
    public static function thoiDiemDongBan(ChuyenTour $chuyen): Carbon
    {
        return Carbon::parse($chuyen->ngayBatDau)
            ->subDay()
            ->endOfDay(); // 23:59:59
    }
}
