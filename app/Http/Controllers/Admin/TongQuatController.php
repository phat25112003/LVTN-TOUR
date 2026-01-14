<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\DatCho;
use App\Models\HoaDon;
use App\Models\NguoiDung;
use App\Models\ThanhToan;
use App\Models\HuongDanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DoanhThuExport;

class TongQuatController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $totalTours = Tour::count();
        $totalBookings = DatCho::count();
        $totalRevenue = \App\Models\Thanhtoan::where('tinhTrangThanhToan', 'Đã thanh toán')
                ->sum('soTien');
        $totalUsers = NguoiDung::count();

        $topBookedTours = Tour::select('tour.maTour', 'tour.tieuDe', 'tour.diemDen', DB::raw('COUNT(datcho.maDatCho) as total_bookings'))
            ->leftJoin('datcho', 'tour.maTour', '=', 'datcho.maTour')
            ->groupBy('tour.maTour', 'tour.tieuDe', 'tour.diemDen')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        $activeHuongDanViens = HuongDanVien::where('trangThai', 'HoatDong')
            ->withCount('chuyenTours')
            ->orderBy('hoTen')
            ->get();

        return view('admin.tongquat.index', compact(
            'totalTours', 'totalBookings', 'totalRevenue', 'totalUsers',
            'topBookedTours', 'activeHuongDanViens', 'admin'
        ));
    }


    public function getChartData(Request $request)
    {
        // Xác định khoảng thời gian
        $tuNgay = $request->filled('tu_ngay') 
            ? Carbon::parse($request->tu_ngay)->startOfDay() 
            : now()->subDays(6)->startOfDay(); // mặc định 7 ngày gần nhất

        $denNgay = $request->filled('den_ngay') 
            ? Carbon::parse($request->den_ngay)->endOfDay() 
            : now()->endOfDay();

        // === BIỂU ĐỒ DOANH THU – DÙNG thanhtoan.soTien (CHUẨN NHẤT) ===
        $doanhThu = DB::table('thanhtoan')
            ->join('datcho', 'thanhtoan.maDatCho', '=', 'datcho.maDatCho')
            ->whereBetween('datcho.ngayDat', [$tuNgay, $denNgay])
            ->selectRaw('DATE(datcho.ngayDat) as date, SUM(thanhtoan.soTien) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $data   = [];
        $current = $tuNgay->copy();

        while ($current <= $denNgay) {
            $dateStr = $current->toDateString();
            $labels[] = $current->format('d/m');
            $data[]   = $doanhThu->get($dateStr, 0);
            $current->addDay();
        }

        $revenueChart = [
            'labels' => $labels,
            'data'   => $data,
            'title'  => 'Doanh Thu Từ ' . $tuNgay->format('d/m/Y') . ' Đến ' . $denNgay->format('d/m/Y')
        ];

        // === BIỂU ĐỒ TRÒN PHƯƠNG THỨC THANH TOÁN – CHUẨN ENUM CỦA BẠN ===
        $raw = DB::table('thanhtoan')
            ->selectRaw('TRIM(LOWER(phuongThucThanhToan)) as method, COUNT(*) as total')
            ->groupBy('method')
            ->pluck('total', 'method');

        $labels = [];
        $data   = [];
        $colors = [];

        foreach ($raw as $method => $total) {
            // Chuẩn hóa tên không dấu + bỏ khoảng trắng thừa
            $clean = trim(strtolower($method));
            $clean = str_replace(['  ', ' '], ' ', $clean); // fix khoảng trắng thừa

            if (str_contains($clean, 'tại văn phòng')) {
                $labels[] = 'tại văn phòng';
                $colors[] = '#077351ff';
                $data[]   = $total;
            }
            elseif (str_contains($clean, 'momo')) {
                $labels[] = 'Ví MoMo';
                $colors[] = '#950584ff';
                $data[]   = $total;
            }
            elseif (str_contains($clean, 'paypal')) {
                $labels[] = 'PayPal';
                $colors[] = '#00266dff';
                $data[]   = $total;
            }
            else {
                $labels[] = ucfirst($method);
                $colors[] = '#95a5a6';
                $data[]   = $total;
            }
        }

        $paymentChart = [
            'labels' => $labels,
            'data'   => $data,
            'colors' => $colors,
            'total'  => array_sum($data)
        ];

        return response()->json([
            'revenueChart' => $revenueChart,
            'paymentChart' => $paymentChart,
        ]);
    }
public function baoCaoDoanhThu()
{
    $admin = auth()->guard('admin')->user();
    return view('admin.baocao.doanhthu', compact('admin'));
}

public function xuatBaoCao(Request $request)
{
    $request->validate([
        'tu_ngay' => 'required|date',
        'den_ngay' => 'required|date|after_or_equal:tu_ngay',
        'loai' => 'required|in:pdf,excel',
        'chi_doanh_thu_da_tt' => 'sometimes|in:1',
    ]);

    // 1. Chuyển thành Carbon ngay từ đầu
    $tuNgayCarbon  = Carbon::parse($request->tu_ngay);
    $denNgayCarbon = Carbon::parse($request->den_ngay);

    // 2. Dùng để query (có giờ)
    $tuNgayQuery  = $tuNgayCarbon->copy()->startOfDay();
    $denNgayQuery = $denNgayCarbon->copy()->endOfDay();

    // 3. Định dạng để hiển thị trên báo cáo (d/m/Y)
    $tuNgayHienThi  = $tuNgayCarbon->format('d/m/Y');
    $denNgayHienThi = $denNgayCarbon->format('d/m/Y');

    // Query dữ liệu
    $query = DatCho::with(['nguoiDung', 'tour', 'chuyenTour', 'thanhToan'])
        ->whereBetween('ngayDat', [$tuNgayQuery, $denNgayQuery]); // DÙNG BIẾN CARBON ĐÃ CÓ GIỜ

    // Tùy chọn: chỉ lấy đơn đã thanh toán
    if ($request->has('chi_doanh_thu_da_tt')) {
        $query->whereHas('thanhToan', fn($q) => $q->where('tinhTrangThanhToan', 'Đã thanh toán'));
    }

    $donDat = $query->orderBy('ngayDat', 'desc')->get();

    // Tính toán tổng
    $tongDonDat = $donDat->count();

    $tongDonThanhToan = $donDat->filter(fn($d) =>
        $d->thanhToan && $d->thanhToan->tinhTrangThanhToan === 'Đã thanh toán'
    )->count();

    $tongDoanhThu = $donDat->sum(fn($d) =>
        $d->thanhToan && $d->thanhToan->tinhTrangThanhToan === 'Đã thanh toán'
            ? (float)$d->thanhToan->soTien  // ÉP KIỂU VỀ SỐ
            : 0
    );

    // Map dữ liệu chi tiết
$chiTiet = $donDat->map(function ($item) {
    $tt = $item->thanhToan;
    $daThanhToan = $tt && $tt->tinhTrangThanhToan === 'Đã thanh toán';

    // ÉP KIỂU CÁC TRƯỜNG TIỀN TỆ VỀ SỐ
    $tongGia = (float)preg_replace('/[^0-9]/', '', $item->tongGia); // bỏ dấu phẩy, chữ...
    $doanhThu = $daThanhToan ? (float)$tt->soTien : 0;

    // Đảm bảo ngày
    $ngayDat = $item->ngayDat ? Carbon::parse($item->ngayDat)->format('d/m/Y H:i') : 'Chưa có';

    $ngayDi = 'Chưa chọn chuyến';
    if ($item->chuyenTour) {
        try {
            $batDau = Carbon::parse($item->chuyenTour->ngayBatDau)->format('d/m');
            $ketThuc = Carbon::parse($item->chuyenTour->ngayKetThuc)->format('d/m/Y');
            $ngayDi = $batDau . ' → ' . $ketThuc;
        } catch (\Exception $e) {
            $ngayDi = 'Lỗi ngày';
        }
    }

    return [
        'ngay_dat'           => $ngayDat,
        'ho_ten'             => $item->hoTen,
        'dien_thoai'         => $item->soDienThoai ?? 'Chưa có',
        'email'              => $item->email ?? 'Chưa có',
        'tour'               => $item->tour?->tieuDe ?? 'Tour đã xóa',
        'ngay_di'            => $ngayDi,
        'so_khach'           => $item->soNguoiLon + $item->soTreEm + $item->soEmBe,
        'tong_tien_dat'      => number_format($tongGia) . ' ₫',
        'thanh_toan'         => $daThanhToan
            ? '<span class="paid">Đã thanh toán</span><br><small>'
              . ($tt->ngayThanhToan ? Carbon::parse($tt->ngayThanhToan)->format('d/m/Y H:i') : '')
              . '<br>' . strtoupper($tt->phuongThucThanhToan)
              . ($tt->maGiaoDich ? ' - ' . $tt->maGiaoDich : '')
              . '</small>'
            : '<span class="unpaid">Chưa thanh toán</span>',
        'doanh_thu'          => $doanhThu,
        'hien_thi_doanh_thu' => $daThanhToan ? number_format($doanhThu) . ' ₫' : '',
    ];
});

    // TRUYỀN ĐÚN ĐÚNG CHO PDF & EXCEL
    if ($request->loai === 'pdf') {
        return $this->xuatPDF(
            $chiTiet,
            $tuNgayHienThi,      // d/m/Y
            $denNgayHienThi,     // d/m/Y
            $tongDonDat,
            $tongDonThanhToan,
            $tongDoanhThu
        );
    }

    return $this->xuatExcel(
        $chiTiet,
        $tuNgayHienThi,      // d/m/Y
        $denNgayHienThi,      // d/m/Y
        $tongDonDat,
        $tongDonThanhToan,
        $tongDoanhThu
    );
}

private function xuatPDF($chiTiet, $tuNgay, $denNgay, $tongDonDat, $tongDonThanhToan, $tongDoanhThu)
{
    // Thay dấu / bằng dấu - để an toàn
    $fileName = 'DoanhThu_' . str_replace('/', '-', $tuNgay) . '_den_' . str_replace('/', '-', $denNgay) . '.pdf';

    $pdf = Pdf::loadView('admin.baocao.pdf_doanhthu', [
        'chiTiet'          => $chiTiet,
        'tuNgay'           => $tuNgay,
        'denNgay'          => $denNgay,
        'tongDonDat'       => $tongDonDat,
        'tongDonThanhToan' => $tongDonThanhToan,
        'tongDoanhThu' => (float)$tongDoanhThu,  
    ])->setPaper('a4', 'landscape');

    return $pdf->stream($fileName);
}

private function xuatExcel($chiTiet, $tuNgay, $denNgay, $tongDonDat, $tongDonThanhToan, $tongDoanhThu)
{
    $fileName = 'DoanhThu_' . str_replace('/', '-', $tuNgay) . '_den_' . str_replace('/', '-', $denNgay) . '.xlsx';
    return Excel::download(
        new class($chiTiet, $tuNgay, $denNgay, $tongDonDat, $tongDonThanhToan, $tongDoanhThu) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithTitle
        {
            private $data, $tuNgay, $denNgay, $tongDonDat, $tongDonThanhToan, $tongDoanhThu;

            public function __construct($data, $tuNgay, $denNgay, $tongDonDat, $tongDonThanhToan, $tongDoanhThu)
            {
                $this->data = $data;
                $this->tuNgay = $tuNgay;           // ← string
                $this->denNgay = $denNgay;         // ← string
                $this->tongDonDat = $tongDonDat;
                $this->tongDonThanhToan = $tongDonThanhToan;
                $this->tongDoanhThu = $tongDoanhThu;
            }

            public function collection()
            {
                $rows = collect([
                    ['BÁO CÁO DOANH THU CHI TIẾT - TRAVELTIME'],
                    ['Từ ngày: ' . $this->tuNgay, 'Đến ngày: ' . $this->denNgay],
                    [],
                    ['Tổng số đơn đặt:', $this->tongDonDat . ' đơn'],
                    ['Tổng số đơn đã thanh toán:', $this->tongDonThanhToan . ' đơn'],
                    ['Tỷ lệ chuyển đổi:', $this->tongDonDat > 0 ? round(($this->tongDonThanhToan / $this->tongDonDat) * 100, 1) . '%' : '0%'],
                    ['Tổng doanh thu thực tế:', number_format($this->tongDoanhThu) . ' ₫'],
                    [],
                ]);

                $rows->push([
                    'STT','Ngày đặt','Họ tên','Điện thoại','Email','Tên tour','Ngày đi','Số khách','Tổng tiền đặt','Trạng thái thanh toán','Doanh thu thực tế (₫)'
                ]);

                foreach ($this->data as $i => $item) {
                    $rows->push([
                        $i + 1,
                        $item['ngay_dat'],
                        $item['ho_ten'],
                        $item['dien_thoai'],
                        $item['email'],
                        $item['tour'],
                        $item['ngay_di'],
                        $item['so_khach'] . ' khách',
                        $item['tong_tien_dat'],
                        strip_tags($item['thanh_toan']),
                        $item['hien_thi_doanh_thu'], // ← đã là string, trống nếu chưa thanh toán
                    ]);
                }

                $rows->push([]);
                $rows->push([
                    'TỔNG CỘNG','','','','','','',
                    $this->tongDonDat . ' đơn','','',
                    $this->tongDonThanhToan . ' đơn đã thanh toán',
                    number_format($this->tongDoanhThu) . ' ₫'
                ]);

                return $rows;
            }

            public function headings(): array { return []; }

            public function title(): string
            {
                return 'Doanh thu ' . $this->tuNgay . ' - ' . $this->denNgay;
            }
        },
        $fileName  // ← tên file an toàn
    );
}
}