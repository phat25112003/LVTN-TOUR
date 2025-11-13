<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\DatCho;
use App\Models\Tour;
use App\Models\ChuyenTour;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ThongTinUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập trước.');
    }

    // Lấy tất cả đặt chỗ kèm tour và chuyến tour
    $datCho = $user->datCho()->with('tour', 'chuyenTour')->orderByDesc('ngayDat')->get();

    $trungThoiGian = [];

    foreach ($datCho as $i => $d1) {
        if (!$d1->chuyenTour) continue; // bỏ qua nếu chưa có chuyến tour

        $start1 = Carbon::parse($d1->chuyenTour->ngayKhoiHanh);
        $end1   = Carbon::parse($d1->chuyenTour->ngayKetThuc);

        foreach ($datCho as $j => $d2) {
            if ($i < $j && $d2->chuyenTour) {
                $start2 = Carbon::parse($d2->chuyenTour->ngayKhoiHanh);
                $end2   = Carbon::parse($d2->chuyenTour->ngayKetThuc);

                // Kiểm tra trùng thời gian
                if ($start1->lte($end2) && $end1->gte($start2)) {
                    $trungThoiGian[] = "{$d1->tour->tieuDe} và {$d2->tour->tieuDe}";
                }
            }
        }
    }

    $canhBao = null;
    if (!empty($trungThoiGian)) {
        $canhBao = "⚠️ Bạn có các tour trùng thời gian: " . implode(', ', $trungThoiGian);
    }

    return view('user.thongtinuser', compact('user', 'datCho', 'canhBao'));
}
public function destroy($maDatCho)
    {
        $datcho = Auth::user()->datCho()->findOrFail($maDatCho);
        
        $datcho->delete();

        return redirect()->route('user.thongtinuser')
                        ->with('success', 'Xóa tour thành công!');
    }

}
