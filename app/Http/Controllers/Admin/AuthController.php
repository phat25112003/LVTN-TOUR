<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuanTri;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.layouts.dashboard', compact('admin'));
    }

    public function profile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập.');
        }

        // Nếu là POST, xử lý cập nhật
        if ($request->isMethod('post')) {
            $request->validate([
                'tenDangNhap' => 'required|string|max:255',
                'email' => 'required|email|unique:quantri,email,' . $admin->maQuanTri . ',maQuanTri',
                'soDienThoai' => 'required|string|max:15',
                'avatar' => 'nullable|image|max:2048',
            ]);

            $data = $request->only('tenDangNhap', 'email', 'soDienThoai');
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = '/storage/' . $avatarPath;
            }

            $adminModel = QuanTri::find($admin->maQuanTri);
            $adminModel->update($data);
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công!'); // Thay đổi redirect
        }

        // Nếu là GET, hiển thị form
        return view('admin.profile', compact('admin'));
    }
   

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'tenDangNhap' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'soDienThoai' => 'nullable|string|max:20',
            'matKhau' => 'nullable|string|min:6|confirmed', // ✅ xác nhận mật khẩu
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'matKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'matKhau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // 🔹 Upload ảnh đại diện
        if ($request->hasFile('avatar')) {
            if ($admin->avatar && Storage::disk('public')->exists('avatars/'.$admin->avatar)) {
                Storage::disk('public')->delete('avatars/'.$admin->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = basename($path);
        }

        // 🔹 Nếu có nhập mật khẩu mới → mã hóa trước khi lưu
        if (!empty($validated['matKhau'])) {
            $validated['matKhau'] = Hash::make($validated['matKhau']);
        } else {
            unset($validated['matKhau']); // không thay đổi nếu để trống
        }

        // 🔹 Cập nhật thông tin admin
        $admin->update($validated);

        return redirect()->route('admin.profile')->with('success', 'Cập nhật thông tin thành công!');
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Không redirect luôn
        // Ở lại trang hiện tại (dashboard), nhưng session mất rồi
        return back()->with('logout', true);
    }



    public function showLoginForm()
    {
        return view('admin.login');
    }

 public function login(Request $request)
    {
        $credentials = $request->validate([
            'tenDangNhap' => 'required|string',
            'matKhau' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt(['tenDangNhap' => $credentials['tenDangNhap'], 'password' => $credentials['matKhau']])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'tenDangNhap' => 'Tên đăng nhập hoặc mật khẩu không đúng.',
        ]);
    }
}

