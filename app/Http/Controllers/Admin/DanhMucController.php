<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;

class DanhMucController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        $danhmucs = DanhMuc::all();
        return view('admin.danhmuc.index', compact('danhmucs', 'admin'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.danhmuc.create' , compact('admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenDanhMuc' => 'required|string|max:255|unique:danhmuc,tenDanhMuc',
        ]);

        DanhMuc::create(['tenDanhMuc' => $request->tenDanhMuc]);
        return redirect()->route('admin.danhmuc.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($maDanhMuc)
    {
        $admin = auth()->guard('admin')->user();
        $danhmuc = DanhMuc::findOrFail($maDanhMuc);
        return view('admin.danhmuc.edit', compact('danhmuc', 'admin'));
    }

    public function update(Request $request, $maDanhMuc)
    {
        $danhmuc = DanhMuc::findOrFail($maDanhMuc);

        $request->validate([
            'tenDanhMuc' => 'required|string|max:255|unique:danhmuc,tenDanhMuc,' . $danhmuc->maDanhMuc . ',maDanhMuc',
        ]);

        $danhmuc->update($request->all());
        return redirect()->route('admin.danhmuc.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($maDanhMuc)
    {
        $danhmuc = DanhMuc::findOrFail($maDanhMuc);
        $danhmuc->delete();
        return redirect()->route('admin.danhmuc.index')->with('success', 'Xóa danh mục thành công!');
    }
}
