<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatCho;
use App\Models\Tour;
use App\Models\ChuyenTour;
use App\Models\GiaTour;
use App\Models\KhachThamGia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\KhuyenMaiSuDung;

class SuaTourDetailController extends Controller
{
// SuaTourDetailController.php
public function index($maDatCho)
{
    $datcho = DatCho::with('khachThamGia')->findOrFail($maDatCho);
    if ($datcho->xacNhan == -1) {
        return redirect()
            ->route('user.thongtinuser')
            ->with('error', 'Tour này đã hết hạn, không thể chỉnh sửa.');
    }


    $tour = Tour::with(['chuyentour.giatour', 'giaTour', 'hinhanh'])
                ->find($datcho->maTour);

    // TÍNH SẴN TẤT CẢ TRONG CONTROLLER
    $chuyenHienTai = $tour->chuyentour
        ->where('maChuyen', $datcho->maChuyen)
        ->first() ?? $tour->chuyentour->first();

    $khachthamgia = $datcho->khachThamGia;

    $soChoConLai = 0;
    if ($chuyenHienTai) {
        $soChoConLai = max(0, $chuyenHienTai->soLuongToiDa - $chuyenHienTai->soLuongDaDat);
    }

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
    $kmUsed = KhuyenMaiSuDung::where('maDatCho', $maDatCho)->with('khuyenmai')->first();
    $ghiChu = $datcho->ghiChu;
    return view('user.suatourdetail', compact(
        'datcho',
        'tour',
        'chuyenHienTai',
        'gia',
        'ngayBatDau_Laravel',
        'ngayKetThuc_Laravel',
        'ngayBatDau_Display',
        'ngayKetThuc_Display',
        'kmUsed',
        'soChoConLai',
        'khachthamgia',
        'ghiChu'
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
        'maChuyen' => 'required|exists:chuyentour,maChuyen',
        'ghiChu'   => 'nullable|string|max:500',
    ]);

    $user   = Auth::guard('web')->user();
    $datcho = DatCho::findOrFail($maDatCho);
    if ($datcho->xacNhan == -1) {
        return redirect()
            ->route('user.thongtinuser')
            ->with('error', 'Tour đã hết hạn, không thể cập nhật.');
    }


    // Kiểm tra quyền sở hữu
    if ($datcho->maNguoiDung !== $user->maNguoiDung) {
        return redirect()->back()->with('error', 'Bạn không có quyền sửa đơn này.');
    }

    $maChuyenCu  = $datcho->maChuyen;
    $maChuyenMoi = $request->maChuyen;

    $tongNguoiCu  = $datcho->soNguoiLon + $datcho->soTreEm + $datcho->soEmBe;
    $tongNguoiMoi = $request->nguoiLon + $request->treEm + $request->emBe;

    // Dùng transaction để an toàn 100%
    DB::transaction(function () use (
        $datcho, $request, $tongNguoiMoi, $tongNguoiCu,
        $maChuyenCu, $maChuyenMoi
    ) {
        // 1. Nếu ĐỔI CHUYẾN → trả chỗ cũ, cộng vào chuyến mới
        if ($maChuyenCu !== $maChuyenMoi) {
            // Trả lại chỗ cho chuyến cũ
            ChuyenTour::where('maChuyen', $maChuyenCu)
                      ->decrement('soLuongDaDat', $tongNguoiCu);

            // Cộng số người mới vào chuyến mới
            ChuyenTour::where('maChuyen', $maChuyenMoi)
                      ->increment('soLuongDaDat', $tongNguoiMoi);
        }
        // 2. Nếu CÙNG CHUYẾN → chỉ điều chỉnh chênh lệch
        else {
            $diff = $tongNguoiMoi - $tongNguoiCu;

            if ($diff !== 0) {
                if ($diff > 0) {
                    ChuyenTour::where('maChuyen', $maChuyenMoi)
                              ->increment('soLuongDaDat', $diff);
                } else {
                    ChuyenTour::where('maChuyen', $maChuyenMoi)
                              ->decrement('soLuongDaDat', abs($diff));
                }
            }
        }

        // Cập nhật đơn đặt chỗ
        $datcho->update([
            'soNguoiLon'          => $request->nguoiLon,
            'soTreEm'             => $request->treEm,
            'soEmBe'              => $request->emBe,
            'tongGia'             => $request->tongGia,
            'diaChi'              => $request->address,
            'soDienThoai'         => $request->phone,
            'phuongThucThanhToan' => $request->phuongThucThanhToan,
            'maChuyen'            => $maChuyenMoi,
            'ghiChu'              => $request->ghiChu ?? null,
        ]);
    });
    // ================================
    // Cập nhật bảng khachthamgia
    // ================================
    KhachThamGia::where('maDatCho', $datcho->maDatCho)->delete();

    $hoTenArr   = $request->hoTenKhach ?? [];
    $gioiTinhArr = $request->gioiTinh ?? [];
    $tuoiArr     = $request->tuoi ?? [];
    $phongDonArr = $request->phongDon ?? [];
    $loaiArr     = $request->loaiKhach ?? [];

    for ($i = 0; $i < count($hoTenArr); $i++) {
        KhachThamGia::create([
            'maDatCho'      => $datcho->maDatCho,
            'hoTenKhach'    => $hoTenArr[$i],
            'gioiTinh'      => $gioiTinhArr[$i],
            'tuoi'          => $tuoiArr[$i],
            'luaChonPhong'  => isset($phongDonArr[$i]) && $phongDonArr[$i] == '1'
                    ? "PhongDon" : "Ghep",
        ]);
    }


    return redirect()->route('user.thongtinuser')
        ->with('success', 'Cập nhật thông tin đặt tour thành công!');
    }

}
