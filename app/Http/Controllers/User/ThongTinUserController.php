<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Auth;

class ThongTinUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy thông tin user hiện tại
        $user = Auth::user();

        // Nếu chưa đăng nhập, chuyển về trang login
        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập trước.');
        }
        $datCho = $user->datCho()->with('tour')->orderByDesc('ngayDat')->get();
        // Trả về view và truyền dữ liệu user
        return view('user.thongtinuser', compact('user', 'datCho'));
    }
}
