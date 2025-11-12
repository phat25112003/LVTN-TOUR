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
public function index($maDatCho)
{
    $datcho = DatCho::findOrFail($maDatCho);
    $tour = Tour::with('chuyentour.giatour')->find($datcho->maTour);

    return view('user.suatourdetail', compact('datcho', 'tour'));
}

public function update(Request $request, $maDatCho)
{
    if (!Auth::guard('web')->check()) {
        return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập.');
    }
    $request->validate([
        'nguoiLon' => 'required|integer|min:1',
        'treEm' => 'required|integer|min:0',
        'emBe' => 'required|integer|min:0',
        'address' => 'required|string',
        'phone' => 'required|string',
    ]);

    $datcho = DatCho::findOrFail($maDatCho);
    $chuyen = ChuyenTour::with('giatour')->findOrFail($request->maChuyen);

    $gia = $chuyen->giatour;
    $tongGia = $gia->nguoiLon * $request->nguoiLon
             + $gia->treEm * $request->treEm
             + $gia->emBe * $request->emBe;

    $datcho->update([
        'soNguoiLon' => $request->nguoiLon,
        'soTreEm' => $request->treEm,
        'soEmBe' => $request->emBe,
        'tongGia' => $tongGia,
        'diaChi' => $request->address,
        'soDienThoai' => $request->phone,
        'phuongThucThanhToan' => $request->phuongThucThanhToan,
    ]);

    return redirect()->route('user.thongtinuser')
                     ->with('success', 'Cập nhật đặt tour thành công!');
    }

}
