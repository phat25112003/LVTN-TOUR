<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\DatCho;
use App\Models\HoaDon;
use App\Models\NguoiDung;
use App\Models\DanhGia;
use App\Models\KhuyenMai;
use App\Models\DanhMuc;
use App\Models\ThanhToan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TongQuatController extends Controller
{
    public function index()
    {
        // 1️⃣ Thống kê tổng quan
        $totalTours = Tour::count();
        $totalBookings = DatCho::count();
        $totalRevenue = HoaDon::sum('soTien');
        $totalUsers = NguoiDung::count();
        $totalReviews = DanhGia::count();
        $totalPromotions = KhuyenMai::count();
        $totalCategories = DanhMuc::count();
        $totalInvoices = HoaDon::count();

        // 2️⃣ Biểu đồ doanh thu theo tháng
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = HoaDon::select(
                DB::raw('MONTH(ngayTao) as month'),
                DB::raw('SUM(soTien) as total_revenue')
            )
            ->whereYear('ngayTao', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_revenue', 'month')
            ->toArray();

        // Tạo mảng doanh thu cho 12 tháng, mặc định là 0
        $revenueData = array_fill(1, 12, 0);
        foreach ($monthlyRevenue as $month => $revenue) {
            $revenueData[$month] = (int) $revenue;
        }
        $revenueData = array_values($revenueData);

        // 3️⃣ Lấy danh sách tour đang hoạt động (tinhTrang = 1)
        $toursDangHoatDong = Tour::where('tinhTrang', operator: 1)
            ->select('maTour', 'tieuDe', 'diemDen', 'giaNguoiLon', 'giaTreEm', 'ngayBatDau', 'ngayKetThuc')
            ->orderBy('ngayBatDau', 'desc')
            ->take(5)
            ->get();

        // 4️⃣ Top tour được đặt nhiều nhất
        $topBookedTours = Tour::select('tour.maTour', 'tour.tieuDe', 'tour.diemDen', DB::raw('COUNT(datcho.maDatCho) as total_bookings'))
            ->leftJoin('datcho', 'tour.maTour', '=', 'datcho.maTour')
            ->groupBy('tour.maTour', 'tour.tieuDe', 'tour.diemDen')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        // 4️⃣ Biểu đồ tròn phương thức thanh toán
        $paymentMethods = ThanhToan::select(
                'phuongThucThanhToan',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('phuongThucThanhToan')
            ->pluck('count', 'phuongThucThanhToan')
            ->toArray();

        // Định dạng tên phương thức thanh toán cho dễ đọc
        $paymentMethods = array_map(function ($key, $value) {
            return [
                'name' => ucfirst(str_replace('_', ' ', $key)), // Chuyển đổi 'tại văn phòng' thành 'Tại Văn Phòng'
                'count' => $value,
            ];
        }, array_keys($paymentMethods), $paymentMethods);

        // 5️⃣ Tạo biểu đồ doanh thu theo tháng
        $revenueChart = [
            'type' => 'bar',
            'data' => [
                'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                'datasets' => [
                    [
                        'label' => 'Doanh thu (VND)',
                        'data' => $revenueData,
                        'backgroundColor' => '#4e73df',
                        'borderColor' => '#4e73df',
                        'borderWidth' => 1,
                    ],
                ],
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'title' => [
                            'display' => true,
                            'text' => 'Doanh thu (VND)',
                        ],
                    ],
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Tháng',
                        ],
                    ],
                ],
            ],
        ];


        // 6️⃣ Tạo biểu đồ tròn phương thức thanh toán VỚI HIỂN THỊ PHẦN TRĂM
        $paymentMethodsRaw = ThanhToan::select(
            'phuongThucThanhToan',
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('phuongThucThanhToan')
        ->pluck('count', 'phuongThucThanhToan')
        ->toArray();

        $totalPayments = array_sum($paymentMethodsRaw);
        $paymentMethods = array_map(function ($key, $value) use ($totalPayments) {
            $percentage = $totalPayments > 0 ? round(($value / $totalPayments) * 100, 1) : 0;
            return [
                'name' => ucfirst(str_replace('_', ' ', $key)),
                'count' => $percentage, // Lưu phần trăm trực tiếp
            ];
        }, array_keys($paymentMethodsRaw), $paymentMethodsRaw);

        $paymentChart = [
            'type' => 'pie',
            'data' => [
                'labels' => array_column($paymentMethods, 'name'),
                'datasets' => [
                    [
                        'data' => array_column($paymentMethods, 'count'),
                        'backgroundColor' => ['#4e73df', '#f400e0ff', '#aebd0bff'],
                        'borderColor' => ['#ffffff', '#ffffff', '#ffffff'],
                        'borderWidth' => 2,
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false, // Cho phép điều chỉnh kích thước linh hoạt
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                        'labels' => [
                            'color' => '#333',
                            'font' => ['size' => 12],
                        ],
                    ],
                    'title' => [
                        'display' => true,
                        'color' => '#333',
                        'font' => ['size' => 16, 'weight' => 'bold'],
                    ],
                    'datalabels' => [
                        'display' => true,
                        'color' => '#fff',
                        'font' => [
                            'weight' => 'bold',
                            'size' => 14,
                        ],
                        'formatter' => function($value) {
                            return $value + ' %';
                        },
                        'anchor' => 'center',
                        'align' => 'center',
                    ],
                ],
                // Tăng kích thước biểu đồ bằng cách điều chỉnh tỷ lệ
                'layout' => [
                    'padding' => [
                        'top' => 1,
                        'bottom' => 1,
                        'left' => 1,
                        'right' => 1,
                    ],
                ],
                // Tùy chỉnh kích thước canvas (tùy thuộc vào view)
                'aspectRatio' => 1.2, // Giảm tỷ lệ để biểu đồ to hơn (giá trị nhỏ hơn làm biểu đồ cao hơn)
            ],
        ];

        // 7️⃣ Trả về view
        return view('admin.tongquat.index', compact(
            'totalTours',
            'totalBookings',
            'totalRevenue',
            'totalUsers',
            'totalReviews',
            'totalPromotions',
            'totalCategories',
            'totalInvoices',
            'revenueChart',
            'paymentChart',
            'toursDangHoatDong',
            'topBookedTours'
        ));
    }
}