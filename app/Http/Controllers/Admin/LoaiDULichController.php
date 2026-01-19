<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiDuLich;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoaiDULichController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $query = LoaiDuLich::query();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where('tenLoai', 'LIKE', "%{$keyword}%");
        }

        // Sắp xếp và phân trang
        $loaiDuLichs = $query->orderBy('maLoai', 'desc')->paginate(15)->withQueryString();

        return view('admin.danhmuc.loaidulich.index', compact('loaiDuLichs', 'admin'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.danhmuc.loaidulich.create', compact('admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenLoai' => 'required|string|max:100|unique:loaidulich,tenLoai',
            'moTa'    => 'nullable|string',
        ], [
            'tenLoai.required' => 'Tên loại du lịch là bắt buộc.',
            'tenLoai.unique'   => 'Tên loại du lịch đã tồn tại.',
            'tenLoai.max'      => 'Tên loại không được quá 100 ký tự.',
        ]);

        LoaiDuLich::create($request->all());

        return redirect()
            ->route('admin.loaidulich.index')
            ->with('success', 'Thêm loại du lịch thành công!');
    }

    public function edit($maLoai)
    {
        $admin = auth()->guard('admin')->user();
        $loai = LoaiDuLich::findOrFail($maLoai);

        return view('admin.danhmuc.loaidulich.edit', compact('loai', 'admin'));
    }

    public function update(Request $request, $maLoai)
    {
        $loai = LoaiDuLich::findOrFail($maLoai);

        $request->validate([
            'tenLoai' => [
                'required',
                'string',
                'max:100',
                Rule::unique('loaidulich', 'tenLoai')->ignore($maLoai, 'maLoai'),
            ],
            'moTa' => 'nullable|string',
        ], [
            'tenLoai.required' => 'Tên loại du lịch là bắt buộc.',
            'tenLoai.unique'   => 'Tên loại du lịch đã tồn tại.',
            'tenLoai.max'      => 'Tên loại không được quá 100 ký tự.',
        ]);

        $loai->update($request->all());

        return redirect()
            ->route('admin.loaidulich.index')
            ->with('success', 'Cập nhật loại du lịch thành công!');
    }


    public function destroy($maLoai)
    {
        $loai = LoaiDuLich::findOrFail($maLoai);

        // Kiểm tra nếu có tour đang dùng loại này
        if ($loai->tours()->exists()) {
            return back()->with('error', 'Không thể xóa vì có tour đang thuộc loại du lịch này!');
        }

        $loai->delete();

        return back()->with('success', 'Xóa loại du lịch thành công!');
    }
}