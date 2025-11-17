<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// ... (Các Model)
use App\Models\Tour;
use App\Models\DatCho;
use App\Models\HoaDon;
use App\Models\NguoiDung;
use App\Models\ThanhToan;
use App\Models\HuongDanVien;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TongQuatController extends Controller
{
    // Giữ lại phương thức index() để tải trang và các chỉ số TĨNH
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        // 1. THỐNG KÊ TỔNG QUAN (Chỉ các chỉ số nhẹ, tính toán nhanh)
        $totalTours         = Tour::count();
        $totalBookings      = DatCho::count();
        $totalRevenue       = HoaDon::sum('soTien');
        $totalUsers         = NguoiDung::count();
        
        // 2. TOP 5 TOUR (Vì đây là bảng HTML tĩnh, không phải biểu đồ)
        $topBookedTours = Tour::select(
                'tour.maTour', 'tour.tieuDe', 'tour.diemDen',
                DB::raw('COUNT(datcho.maDatCho) as total_bookings')
            )
            ->leftJoin('datcho', 'tour.maTour', '=', 'datcho.maTour')
            ->groupBy('tour.maTour', 'tour.tieuDe', 'tour.diemDen')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        // THÊM: Chỉ lấy HDV Hoạt động
        $activeHuongDanViens = HuongDanVien::where('trangThai', 'HoatDong')
                                       ->withCount('chuyenTours')
                                       ->orderBy('hoTen')
                                       ->get();

        // CHỈ TRUYỀN DỮ LIỆU TĨNH
        return view('admin.tongquat.index', compact(
            'totalTours',
            'totalBookings',
            'totalRevenue',
            'totalUsers',
            'topBookedTours',
            'activeHuongDanViens',
            'admin'
        ));

    }

    public function getChartData()
    {

        $currentYear = Carbon::now()->year;
        $monthlyRevenue = HoaDon::select(
                DB::raw('MONTH(ngayTao) as month'),
                DB::raw('SUM(soTien) as total_revenue')
            )
            ->whereYear('ngayTao', $currentYear)
            ->groupBy('month')->orderBy('month')->pluck('total_revenue', 'month')->toArray();

        $revenueData = array_values(array_replace(array_fill(1, 12, 0), $monthlyRevenue));
        
        $revenueChart = [
            'labels' => [
                'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
            ],
            'data' => $revenueData,
            'currentYear' => $currentYear,
        ];

        // 2. Dữ liệu Thanh toán
        $paymentMethodsRaw = ThanhToan::select('phuongThucThanhToan', DB::raw('COUNT(*) as count'))
            ->groupBy('phuongThucThanhToan')->pluck('count', 'phuongThucThanhToan')->toArray();

        $totalPayments = array_sum($paymentMethodsRaw);
        $paymentMethods = array_map(function ($key, $value) use ($totalPayments) {
            $percentage = $totalPayments > 0 ? round(($value / $totalPayments) * 100, 1) : 0;
            return ['name' => ucfirst(str_replace('_', ' ', $key)), 'count' => $percentage];
        }, array_keys($paymentMethodsRaw), $paymentMethodsRaw);

        $paymentChart = [
            'labels' => array_column($paymentMethods, 'name'),
            'data' => array_column($paymentMethods, 'count'),
        ];
        
        // Trả về JSON, không cần render View
        return response()->json([
            'revenueChart' => $revenueChart,
            'paymentChart' => $paymentChart,
        ]);
    }
}