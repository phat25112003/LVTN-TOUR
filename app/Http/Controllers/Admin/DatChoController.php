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
        // Eager load quan hệ thanhtoan VÀ hoadon
        $datChos = DatCho::with(['nguoiDung', 'tour', 'thanhtoan', 'hoadon'])
                             ->orderBy('ngayDat', 'desc')
                             ->get();
        return view('admin.datcho.index', compact('datChos','admin'));
    }

    public function xacNhan($maDatCho)
    {
        // 1. Tìm kiếm đặt chỗ và eager load thông tin thanh toán
        $datCho = DatCho::with('thanhtoan')->findOrFail($maDatCho);
        
        // 2. Kiểm tra nếu đặt chỗ chưa được xác nhận (xacNhan == 0)
        if ($datCho->xacNhan == 0) {
            
            // 3. Thực hiện quy tắc nghiệp vụ: Phải kiểm tra trạng thái thanh toán
            if ($datCho->thanhtoan && $datCho->thanhtoan->tinhTrangThanhToan === 'Đã thanh toán') {
                
                // Nếu Đã thanh toán, tiến hành xác nhận
                $datCho->update(['xacNhan' => 1]);
                
                return redirect()->route('admin.datcho.index')->with('success', 'Xác nhận tour thành công.');
            } else {
                // Nếu Chưa thanh toán hoặc không tìm thấy bản ghi thanh toán
                return redirect()->route('admin.datcho.index')->with('error', 'Không thể xác nhận: Đặt chỗ chưa được thanh toán.');
            }
        }
        
        // Trạng thái đã được xác nhận từ trước
        return redirect()->route('admin.datcho.index')->with('warning', 'Đặt chỗ này đã được xác nhận trước đó.');
    }

    public function xacNhanThanhToan($maDatCho)
    {
        $datCho = DatCho::with('thanhtoan')->findOrFail($maDatCho);

        // 1. Kiểm tra phương thức có phải là 'tại văn phòng'
        if ($datCho->phuongThucThanhToan !== 'tại văn phòng') {
            // Sửa tên route: datcho.index
            return redirect()->route('admmin.datcho.index')->with('error', 'Không thể xác nhận thanh toán thủ công: Phương thức không phải là "tại văn phòng".');
        }

        // 2. Kiểm tra và cập nhật bản ghi thanh toán
        $thanhToan = $datCho->thanhtoan;

        // Nếu bản ghi thanh toán chưa tồn tại, tạo mới.
        if (!$thanhToan) {
            $thanhToan = ThanhToan::create([
                'maDatCho' => $datCho->maDatCho,
                'phuongThucThanhToan' => $datCho->phuongThucThanhToan,
                'soTien' => $datCho->tongGia,
                'tinhTrangThanhToan' => 'Đã thanh toán',
                'maGiaoDich' => 'TT_VP_' . $datCho->maDatCho . '_' . now()->format('Ymd'),
                'ngayThanhToan' => now(),
            ]);
            
            // Sửa tên route: datcho.index
            return redirect()->route('admin.datcho.index')->with('success', 'Đã tạo và Xác nhận thanh toán thành công (Thanh toán tại văn phòng).');

        } 
        
        // Nếu bản ghi thanh toán đã tồn tại nhưng chưa thanh toán
        elseif ($thanhToan->tinhTrangThanhToan !== 'Đã thanh toán') {
            
            $thanhToan->update([
                'tinhTrangThanhToan' => 'Đã thanh toán',
                'ngayThanhToan' => now(),
            ]);

            // Sửa tên route: datcho.index
            return redirect()->route('admin.datcho.index')->with('success', 'Đã cập nhật trạng thái thanh toán thành công.');
        } 
        
        // Đã thanh toán rồi
        else {
            // Sửa tên route: datcho.index
            return redirect()->route('admin.datcho.index')->with('warning', 'Đặt chỗ này đã được thanh toán trước đó.');
        }
        $datChos = DatCho::with(['tour', 'chuyentour', 'thanhtoan'])
                        ->orderBy('ngayDat', 'desc')
                        ->get();
        return view('admin.datcho.index', compact('datChos', 'admin'));
    }

    public function show($maDatCho)
    {
        $admin = auth()->guard('admin')->user();
        $datCho = DatCho::with([
                'tour' => fn($q) => $q->select('maTour', 'tieuDe', 'thoiGian'), 
                'chuyentour' => fn($q) => $q->select(
                    'maChuyen', 'maTour', 'diemKhoiHanh', 'maHDV', 'phuongTien',
                    'ngayBatDau', 'ngayKetThuc', 'soLuongToiDa', 'soLuongDaDat', 'tinhTrangChuyen'
                ),
                'chuyentour.giatour' => fn($q) => $q->select('maChuyen', 'nguoiLon', 'treEm', 'emBe'),
                'chuyentour.huongdanvien' => fn($q) => $q->select('maHDV', 'hoTen'),
                'thanhtoan',
                'hoadon'
            ])->findOrFail($maDatCho);

        // Tính giá
        $gia = $datCho->chuyentour?->giatour;
        $giaNguoiLon = $gia?->nguoiLon ?? 0;
        $giaTreEm    = $gia?->treEm ?? 0;
        $giaEmBe     = $gia?->emBe ?? 0;

        $tongTienTinhToan = 
            ($datCho->soNguoiLon * $giaNguoiLon) +
            ($datCho->soTreEm * $giaTreEm) +
            ($datCho->soEmBe * $giaEmBe);

        return view('admin.datcho.show', compact(
            'datCho', 'giaNguoiLon', 'giaTreEm', 'giaEmBe', 'tongTienTinhToan', 'admin'
        ));
    }

    /**
     * Xuất & Gửi Hóa Đơn qua Email
     */
    public function sendInvoice($maDatCho)
    {
        $datCho = DatCho::with([
                'tour',
                'chuyentour',
                'chuyentour.giatour',
                'chuyentour.huongdanvien',  // Lấy tên HDV
                'thanhtoan',
                'hoadon'
            ])->findOrFail($maDatCho);

        // Kiểm tra thanh toán
        if (!$datCho->thanhtoan || $datCho->thanhtoan->tinhTrangThanhToan !== 'Đã thanh toán') {
            return back()->with('error', 'Không thể gửi hóa đơn: Chưa thanh toán.');
        }

        // Tính giá (đảm bảo)
        $gia = $datCho->chuyentour?->giatour;
        $giaNguoiLon = $gia?->nguoiLon ?? 0;
        $giaTreEm    = $gia?->treEm ?? 0;
        $giaEmBe     = $gia?->emBe ?? 0;

        $tongTienTinhToan = 
            ($datCho->soNguoiLon * $giaNguoiLon) +
            ($datCho->soTreEm * $giaTreEm) +
            ($datCho->soEmBe * $giaEmBe);

        // Tạo/cập nhật hóa đơn
        $hoaDon = HoaDon::updateOrCreate(
            ['maDatCho' => $datCho->maDatCho],
            [
                'soTien' => $tongTienTinhToan,
                'ngayTao' => now(),
                'chiTiet' => "Hóa đơn tour: {$datCho->tour->tieuDe} - Mã chuyến: {$datCho->maChuyen}",
                'trangThai' => 'Đã gửi'
            ]
        );

        try {
            Mail::to($datCho->email)->send(new InvoiceMail($datCho, $hoaDon, $tongTienTinhToan));
            return back()->with('success', "Hóa đơn đã được gửi đến: {$datCho->email}");
        } catch (\Exception $e) {
            Log::error("Lỗi gửi email hóa đơn #{$maDatCho}: " . $e->getMessage());
            return back()->with('error', 'Gửi thất bại. Vui lòng kiểm tra email hoặc cấu hình mail.');
        }
    }
}