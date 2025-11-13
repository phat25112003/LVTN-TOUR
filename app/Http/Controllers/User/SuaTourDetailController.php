<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatCho;
use App\Models\Tour;
use App\Models\ChuyenTour;
use App\Models\GiaTour;
use Illuminate\Support\Facades\Auth;

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

    $ngayKhoiHanh_Laravel = $chuyenHienTai
        ? \Carbon\Carbon::parse($chuyenHienTai->ngayBatDau)->format('Y-m-d')
        : '';
    $ngayKetThuc_Laravel = $chuyenHienTai
        ? \Carbon\Carbon::parse($chuyenHienTai->ngayKetThuc)->format('Y-m-d')
        : '';

    $ngayKhoiHanh_Display = $chuyenHienTai
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
        'ngayKhoiHanh_Laravel',
        'ngayKetThuc_Laravel',
        'ngayKhoiHanh_Display',
        'ngayKetThuc_Display'
    ));
}

public function update(Request $request, $maDatCho)
{
    if (!Auth::guard('web')->check()) {
        return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập.');
    }

    // Validate đầy đủ (bổ sung ngày + maChuyen)
    $request->validate([
        'nguoiLon' => 'required|integer|min:1',
        'treEm'    => 'required|integer|min:0',
        'emBe'     => 'required|integer|min:0',
        'address'  => 'required|string',
        'phone'    => 'required|string',
        'phuongThucThanhToan' => 'required|string',
        
        // BẮT BUỘC THÊM 3 TRƯỜNG NÀY
        'ngayKhoiHanh' => 'required|date',
        'ngayKetThuc'  => 'required|date|after_or_equal:ngayKhoiHanh',
        'maChuyen'     => 'required|exists:chuyentour,maChuyen',
    ]);

    $datcho = DatCho::findOrFail($maDatCho);

    // Kiểm tra chuyến tour có thuộc tour này không (bảo mật)
    $chuyen = ChuyenTour::where('maChuyen', $request->maChuyen)
                        ->where('maTour', $datcho->maTour)
                        ->with('giatour')
                        ->firstOrFail();

    $gia = $chuyen->giatour;

    $tongGia = $gia->nguoiLon * $request->nguoiLon
             + $gia->treEm   * $request->treEm
             + $gia->emBe    * $request->emBe;

    // CẬP NHẬT ĐẦY ĐỦ – THÊM 3 TRƯỜNG QUAN TRỌNG
    $datcho->update([
        'soNguoiLon'          => $request->nguoiLon,
        'soTreEm'             => $request->treEm,
        'soEmBe'              => $request->emBe,
        'tongGia'             => $tongGia,
        'diaChi'              => $request->address,
        'soDienThoai'         => $request->phone,
        'phuongThucThanhToan' => $request->phuongThucThanhToan,

        // 3 DÒNG QUAN TRỌNG NHẤT – BẠN ĐÃ THIẾU!
        'ngayKhoiHanh'        => $request->ngayKhoiHanh,  // 2025-11-15
        'ngayKetThuc'         => $request->ngayKetThuc,   // 2025-11-17
        'maChuyen'            => $request->maChuyen,
    ]);

    return redirect()->route('user.thongtinuser')
                     ->with('success', 'Cập nhật đặt tour thành công.');
}
}
