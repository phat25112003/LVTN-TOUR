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
    return view('user.thongtinuser', compact('user', 'datCho'));
}
public function destroy($maDatCho)
    {
        $datcho = Auth::user()->datCho()->findOrFail($maDatCho);
        
        $datcho->delete();

        return redirect()->route('user.thongtinuser')
                        ->with('success', 'Xóa tour thành công!');
    }

}
