<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoiDung;

class DangKyController extends Controller
{
    public function showRegistrationForm()
    {
        return view('user.dangky');
    }
    public function register(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'tenDangNhap' => 'required|string|max:50|unique:nguoidung,tenDangNhap',
            'hoTen' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:nguoidung,email',
            'matKhau' => 'required|string|min:8|confirmed',
            'soDienThoai' => 'required|string|max:15',
            'gioiTinh' => 'required|in:Nam,Nữ,Khác',
        ]);

        // Create a new user
        $user = new NguoiDung();
        $user->tenDangNhap = $request->tenDangNhap;
        $user->hoTen = $request->hoTen;
        $user->email = $request->email;
        $user->matKhau = bcrypt($request->matKhau);
        $user->soDienThoai = $request->soDienThoai;
        $user->gioiTinh = $request->gioiTinh;
        $user->tinhTrang = 1; // Default status
        $user->save();

        // Redirect to login page with success message
        return redirect()->route('user.login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}
