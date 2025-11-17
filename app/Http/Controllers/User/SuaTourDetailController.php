<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatCho;
use App\Models\Tour;
use App\Models\ChuyenTour;
use App\Models\GiaTour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuaTourDetailController extends Controller
{
// SuaTourDetailController.php
public function index($maDatCho)
{
    $datcho = DatCho::findOrFail($maDatCho);

    $tour = Tour::with(['chuyentour.giatour', 'giaTour', 'hinhanh'])
                ->find($datcho->maTour);

    // TÍNH SẴN TẤT CẢ TRONG CONTROLLER
    $chuyenHienTai = $tour->chuyentour
        ->where('maChuyen', $datcho->maChuyen)
        ->first() ?? $tour->chuyentour->first();

    $gia = $chuyenHienTai?->giatour ?? $tour->giaTour->first();

    $ngayBatDau_Laravel = $chuyenHienTai
        ? \Carbon\Carbon::parse($chuyenHienTai->ngayBatDau)->format('Y-m-d')
        : '';
    $ngayKetThuc_Laravel = $chuyenHienTai
        ? \Carbon\Carbon::parse($chuyenHienTai->ngayKetThuc)->format('Y-m-d')
        : '';

    $ngayBatDau_Display = $chuyenHienTai
        ? \Carbon\Carbon::parse($chuyenHienTai->ngayBatDau)->format('d/m/Y')
        : '--/--/----';
    $ngayKetThuc_Display = $chuyenHienTai
        ? \Carbon\Carbon::parse($chuyenHienTai->ngayKetThuc)->format('d/m/Y')
        : '--/--/----';

    return view('user.suatourdetail', compact(
        'datcho',
        'tour',
        'chuyenHienTai',
        'gia',
        'ngayBatDau_Laravel',
        'ngayKetThuc_Laravel',
        'ngayBatDau_Display',
        'ngayKetThuc_Display'
    ));
}

public function update(Request $request, $maDatCho)
{
    if (!Auth::guard('web')->check()) {
        return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập.');
    }

    $request->validate([
        'nguoiLon' => 'required|integer|min:1',
        'treEm'    => 'required|integer|min:0',
        'emBe'     => 'required|integer|min:0',
        'address'  => 'required|string|max:255',
        'phone'    => 'required|string|max:20',
        'phuongThucThanhToan' => 'required|in:momo,paypal,tại văn phòng',
        'maChuyen'     => 'required|exists:chuyentour,maChuyen',
    ]);

    $user = Auth::guard('web')->user();
    $datcho = DatCho::findOrFail($maDatCho);

    // Kiểm tra quyền sở hữu đơn đặt chỗ
    if ($datcho->maNguoiDung !== $user->maNguoiDung) {
        return redirect()->back()->with('error', 'Bạn không có quyền sửa đơn này.');
    }

    // Lấy chuyến tour mới người dùng chọn
    $chuyenMoi = ChuyenTour::where('maChuyen', $request->maChuyen)
                            ->where('maTour', $datcho->maTour)
                            ->firstOrFail();

    $startMoi = Carbon::parse($chuyenMoi->ngayBatDau);
    $endMoi   = Carbon::parse($chuyenMoi->ngayKetThuc);

    // === KIỂM TRA TRÙNG THỜI GIAN VỚI CÁC TOUR KHÁC (TRỪ CHÍNH ĐƠN NÀY) ===
    $datChoKhac = DB::table('datcho')
        ->join('chuyentour', 'datcho.maChuyen', '=', 'chuyentour.maChuyen')
        ->join('tour', 'datcho.maTour', '=', 'tour.maTour')
        ->where('datcho.maNguoiDung', $user->maNguoiDung)
        ->where('datcho.maDatCho', '!=', $maDatCho) // Loại trừ chính đơn đang sửa
        ->select('tour.tieuDe', 'chuyentour.ngayBatDau', 'chuyentour.ngayKetThuc')
        ->get();

    $trungVoi = [];

    foreach ($datChoKhac as $d) {
        $start = Carbon::parse($d->ngayBatDau);
        $end   = Carbon::parse($d->ngayKetThuc);

        // Nếu có bất kỳ ngày nào trùng nhau → chặn
        if ($startMoi->lte($end) && $endMoi->gte($start)) {
            $trungVoi[] = $d->tieuDe;
        }
    }

    if (!empty($trungVoi)) {
        $danhSach = implode(', ', $trungVoi);
        return redirect()->back()
            ->with('error', "Không thể cập nhật! Chuyến tour mới trùng thời gian với tour đã đặt: $danhSach")
            ->withInput();
    }

    // === TÍNH LẠI GIÁ ===
    $gia = $chuyenMoi->giatour;

    if (!$gia) {
        return redirect()->back()->with('error', 'Chuyến tour chưa có bảng giá.');
    }

    $tongGia = $gia->nguoiLon * $request->nguoiLon +
               $gia->treEm   * $request->treEm +
               $gia->emBe    * $request->emBe;

    // === CẬP NHẬT THÔNG TIN ===
    $datcho->update([
        'soNguoiLon'          => $request->nguoiLon,
        'soTreEm'             => $request->treEm,
        'soEmBe'              => $request->emBe,
        'tongGia'             => $tongGia,
        'diaChi'              => $request->address,
        'soDienThoai'         => $request->phone,
        'phuongThucThanhToan' => $request->phuongThucThanhToan,
        'maChuyen'            => $request->maChuyen,
    ]);

    return redirect()->route('user.thongtinuser')
        ->with('success', 'Cập nhật thông tin đặt tour thành công!');
}
}
