<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatCho;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Log;

class DatChoController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        $datChos = DatCho::with(['tour', 'thanhtoan', 'hoadon'])
            ->orderBy('ngayDat', 'desc')
            ->get();

        return view('admin.datcho.index', compact('datChos','admin'));
    }

    /**
     * Xem chi tiết đặt chỗ
     */
    public function show($maDatCho)
    {
        $admin = auth()->guard('admin')->user();
        $datCho = DatCho::with([
                'tour' => fn($q) => $q->select('maTour', 'tieuDe'),
                'chuyen' => fn($q) => $q->select(
                    'maChuyen', 'maTour', 'diemKhoiHanh', 'huongDanVien', 'phuongTien',
                    'ngayBatDau', 'ngayKetThuc', 'soLuongToiDa', 'soLuongDaDat', 'tinhTrangChuyen'
                ),
                'chuyen.gia' => fn($q) => $q->select('maChuyen', 'nguoiLon', 'treEm', 'emBe'), // LẤY 3 CỘT
                'thanhtoan' => fn($q) => $q->select('maDatCho', 'tinhTrangThanhToan', 'maGiaoDich', 'ngayThanhToan'),
                'hoadon' => fn($q) => $q->select('maDatCho', 'trangThai')
            ])->findOrFail($maDatCho);

        // LẤY GIÁ TỪ 3 CỘT
        $gia = $datCho->chuyen?->gia;

        $giaNguoiLon = $gia->nguoiLon ?? 0;
        $giaTreEm    = $gia->treEm ?? 0;
        $giaEmBe     = $gia->emBe ?? 0;

        $tongTienTinhToan = 
            ($datCho->soNguoiLon * $giaNguoiLon) +
            ($datCho->soTreEm * $giaTreEm) +
            ($datCho->soEmBe * $giaEmBe);

        return view('admin.datcho.show', compact('datCho', 'giaNguoiLon', 'giaTreEm', 'giaEmBe', 'tongTienTinhToan','admin'));
    }

    /**
     * Xuất & Gửi Hóa Đơn qua Email
     */
    public function sendInvoice($maDatCho)
    {
        $datCho = DatCho::with([
                'tour', 'chuyen', 'chuyen.gia', 'thanhtoan', 'hoadon'
            ])->findOrFail($maDatCho);

        // Kiểm tra thanh toán
        if (!$datCho->thanhtoan || $datCho->thanhtoan->tinhTrangThanhToan !== 'Đã thanh toán') {
            return back()->with('error', 'Không thể gửi hóa đơn: Chưa thanh toán.');
        }

        // Tạo/cập nhật hóa đơn
        $hoaDon = HoaDon::updateOrCreate(
            ['maDatCho' => $datCho->maDatCho],
            [
                'soTien' => $datCho->tongGia,
                'ngayTao' => now(),
                'chiTiet' => "Hóa đơn tour: {$datCho->tour->tieuDe} - Mã chuyến: {$datCho->maChuyen}",
                'trangThai' => 'Đã gửi'
            ]
        );

        // Gửi email
        try {
            Mail::to($datCho->email)->send(new InvoiceMail($datCho, $hoaDon));

            return back()->with('success', "Hóa đơn đã được gửi đến: {$datCho->email}");
        } catch (\Exception $e) {
            Log::error("Lỗi gửi email hóa đơn #{$maDatCho}: " . $e->getMessage());
            return back()->with('error', 'Gửi thất bại. Vui lòng kiểm tra email hoặc cấu hình mail.');
        }
    }
}