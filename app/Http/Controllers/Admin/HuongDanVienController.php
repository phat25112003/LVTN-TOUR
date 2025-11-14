<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HuongDanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
class HuongDanVienController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $query = HuongDanVien::query();

        if ($request->filled('search')) {
            $query->where('hoTen', 'like', '%' . $request->search . '%')
                  ->orWhere('soDienThoai', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('trangThai')) {
            $query->where('trangThai', $request->trangThai);
        }

        $hdvs = $query->orderBy('hoTen')->paginate(10);

        return view('admin.huongdanvien.index', compact('hdvs','admin'));
    }

    public function show($maHDV)
    {
        $admin = auth()->guard('admin')->user();
        $hdv = HuongDanVien::findOrFail($maHDV);
        return view('admin.huongdanvien.show', compact('hdv','admin'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.huongdanvien.create', compact('admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hoTen'       => 'required|string|max:100',
            'soDienThoai' => 'required|string|max:15|unique:huongdanvien',
            'email'       => 'nullable|email|max:100',
            'diaChi'      => 'nullable|string|max:255',
            'soCCCD'      => 'nullable|string|max:20|unique:huongdanvien',
            'ngaySinh'    => 'nullable|date',
            'gioiTinh'    => 'required|in:Nam,Nu',
            'ghiChu'       => 'required|string',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'trangThai'   => 'required|in:HoatDong,NgungHoatDong',
        ]);

        // THÊM 'avatar' VÀO MẢNG only()
        $data = $request->only([
            'hoTen', 'soDienThoai', 'email', 'diaChi', 'soCCCD',
            'ngaySinh', 'gioiTinh', 'ghiChu', 'trangThai'
        ]);

        // LƯU ẢNH + LƯU TÊN FILE VÀO DB
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'hdv_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatar-hdv', $filename, 'public'); // Lưu vào storage/app/public/avatar-hdv
            $data['avatar'] = $filename; // LƯU TÊN FILE VÀO DB
        }

        HuongDanVien::create($data);

        return redirect()->route('admin.huongdanvien.index')
                        ->with('success', 'Thêm hướng dẫn viên thành công!');
    }

    public function edit($maHDV)
    {
        $admin = auth()->guard('admin')->user();
        $hdv = HuongDanVien::findOrFail($maHDV);
        return view('admin.huongdanvien.edit', compact('hdv','admin'));
    }

    public function update(Request $request, $maHDV)
    {
        $hdv = HuongDanVien::findOrFail($maHDV);

        $request->validate([
            'hoTen'       => 'required|string|max:100',
            'soDienThoai' => 'required|string|max:15|unique:huongdanvien,soDienThoai,' . $maHDV . ',maHDV',
            'email'       => 'nullable|email|max:100',
            'diaChi'      => 'nullable|string|max:255',
            'soCCCD'      => 'nullable|string|max:20|unique:huongdanvien,soCCCD,' . $maHDV . ',maHDV',
            'ngaySinh'    => 'nullable|date',
            'gioiTinh'    => 'required|in:Nam,Nu',
            'ghiChu'      => 'required|string',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'trangThai'   => 'required|in:HoatDong,NgungHoatDong',
        ]);

        $data = $request->only([
            'hoTen', 'soDienThoai', 'email', 'diaChi', 'soCCCD', 
            'ngaySinh', 'gioiTinh', 'ghiChu', 'trangThai',  
        ]);

        if ($request->hasFile('avatar')) {
                // XÓA ẢNH CŨ
                if ($hdv->avatar) {
                    Storage::disk('public')->delete('avatar-hdv/' . $hdv->avatar);
                }

                // LƯU ẢNH MỚI + LƯU TÊN FILE VÀO DB
                $file = $request->file('avatar');
                $filename = 'hdv_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('avatar-hdv', $filename, 'public');
                $data['avatar'] = $filename;
        }

        $hdv->update($data);

        return redirect()->route('admin.huongdanvien.index')
                        ->with('success', 'Cập nhật thành công!');
    }

    public function destroy($maHDV)
    {
        $hdv = HuongDanVien::findOrFail($maHDV);

        if ($hdv->chuyenTours()->exists()) {
            return back()->with('error', 'Không thể xóa HDV đang có chuyến!');
        }

        if ($hdv->avatar) {
            Storage::disk('public')->delete($hdv->avatar);
        }

        $hdv->delete();

        return redirect()->route('admin.huongdanvien.index')->with('success', 'Xóa thành công!');
    }
}