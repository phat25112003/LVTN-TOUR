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
        // CHỈ HIỂN THỊ FORM - KHÔNG XỬ LÝ POST
        return view('admin.profile', compact('admin'));
    }
   

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\QuanTri $admin */
        $admin = Auth::guard('admin')->user();

        // Xác định rule động cho mật khẩu
        $rules = [
            'tenDangNhap' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'soDienThoai' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ];

        $messages = [
            'matKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'matKhau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];

        // Nếu có nhập mật khẩu mới → yêu cầu mật khẩu hiện tại + xác nhận
        if ($request->filled('matKhau')) {
            $rules['matKhauHienTai'] = ['required', function ($attribute, $value, $fail) use ($admin) {
                if (!Hash::check($value, $admin->matKhau)) {
                    $fail('Mật khẩu hiện tại không đúng.');
                }
            }];
            $rules['matKhau'] = 'required|string|min:6|confirmed';
        } else {
            $rules['matKhauHienTai'] = 'nullable';
        }

        $validated = $request->validate($rules, $messages);

        // Upload ảnh đại diện
        if ($request->hasFile('avatar')) {
            // XÓA ẢNH CŨ (nếu có)
            if ($admin->avatar && Storage::disk('public')->exists('avatars/' . $admin->avatar)) {
                Storage::disk('public')->delete('avatars/' . $admin->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = basename($path); // → abc123.jpg
        }

        // Cập nhật mật khẩu nếu có
        if (!empty($validated['matKhau'])) {
            $validated['matKhau'] = Hash::make($validated['matKhau']);
        } else {
            unset($validated['matKhau']);
        }

        // Loại bỏ matKhauHienTai khỏi dữ liệu lưu
        unset($validated['matKhauHienTai']);

        // Cập nhật
        $admin->update($validated);

        return redirect()->route('admin.profile')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // Sử dụng guard admin
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login'); // Chuyển hướng đến admin.login
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
            return redirect()->route('admin.tongquat.index');
        }

        return back()->withErrors([
            'tenDangNhap' => 'Tên đăng nhập hoặc mật khẩu không đúng.',
        ]);
    }
}

