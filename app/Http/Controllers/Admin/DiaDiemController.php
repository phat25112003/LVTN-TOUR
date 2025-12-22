<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiaDiem;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiaDiemController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $search = $request->get('search');

        $diadiem = DiaDiem::with('danhMuc')
            ->when($search, function ($query, $search) {
                return $query->where('tenDiaDiem', 'like', "%{$search}%");
            })
            ->orderBy('tenDiaDiem')
            ->paginate(15);

        return view('admin.danhmuc.diadiem.index', compact('diadiem', 'search', 'admin'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        $danhmuc = DanhMuc::orderBy('tenDanhMuc')->get();
        return view('admin.danhmuc.diadiem.create', compact('danhmuc','admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenDiaDiem' => 'required|string|max:255|unique:diadiem,tenDiaDiem',
            'maDanhMuc'  => 'required|exists:danhmuc,maDanhMuc',
        ], [
            'tenDiaDiem.required' => 'Tên địa điểm không được để trống',
            'tenDiaDiem.unique'   => 'Tên địa điểm này đã tồn tại',
            'maDanhMuc.required'  => 'Vui lòng chọn danh mục',
        ]);

        DiaDiem::create($request->only('tenDiaDiem', 'maDanhMuc'));

        return redirect()
            ->route('admin.diadiem.index')
            ->with('success', 'Thêm địa điểm thành công!');
    }

    public function edit($id)
    {
        $admin = auth()->guard('admin')->user();
        $diadiem = DiaDiem::findOrFail($id);
        $danhmuc = DanhMuc::orderBy('tenDanhMuc')->get();

        return view('admin.danhmuc.diadiem.edit', compact('diadiem', 'danhmuc','admin'));
    }

    public function update(Request $request, $id)
    {
        $diadiem = DiaDiem::findOrFail($id);

        $request->validate([
            'tenDiaDiem' => 'required|string|max:255|unique:diadiem,tenDiaDiem,' . $id . ',maDiaDiem',
            'maDanhMuc'  => 'required|exists:danhmuc,maDanhMuc',
        ], [
            'tenDiaDiem.required' => 'Tên địa điểm không được để trống',
            'tenDiaDiem.unique'   => 'Tên địa điểm này đã tồn tại',
            'maDanhMuc.required'  => 'Vui lòng chọn danh mục',
        ]);

        $diadiem->update($request->only('tenDiaDiem', 'maDanhMuc'));

        return redirect()
            ->route('admin.diadiem.index')
            ->with('success', 'Cập nhật địa điểm thành công!');
    }

    public function destroy($id)
    {
        $diadiem = DiaDiem::findOrFail($id);
        
        $diadiem->delete();

        return redirect()
            ->route('admin.diadiem.index')
            ->with('success', 'Xóa địa điểm thành công!');
    }
}