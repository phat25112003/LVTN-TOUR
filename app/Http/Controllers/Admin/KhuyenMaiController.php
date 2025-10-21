<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $khuyenMais = KhuyenMai::with('tour')->get();
        return view('admin.khuyenmai.index', compact('khuyenMais'));
    }

    public function create()
    {
        $tours = \App\Models\Tour::all();
        return view('admin.khuyenmai.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'maKhuyenMai' => 'required|unique:khuyenmai,maKhuyenMai',
            'tenKhuyenMai' => 'required',
            'mucGiam' => 'required|numeric|min:0',
            'loaiGiam' => 'required|in:Phan tram,Tien mat',
            'ngayBatDau' => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayBatDau',
            'maTour' => 'nullable|exists:tour,maTour',
            'apDungTatCaTour' => 'boolean', // Xác nhận là boolean
        ]);

        // Nếu áp dụng cho tất cả tour, đặt maTour = null
        $data = $request->all();
        if ($request->has('apDungTatCaTour') && $request->apDungTatCaTour) {
            $data['maTour'] = null;
        }

        KhuyenMai::create($data);
        return redirect()->route('admin.khuyenmai.index')->with('success', 'Thêm khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        $tours = \App\Models\Tour::all();
        return view('admin.khuyenmai.edit', compact('khuyenMai', 'tours'));
    }

    public function update(Request $request, $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        $request->validate([
            'maKhuyenMai' => 'required|unique:khuyenmai,maKhuyenMai,' . $id,
            'tenKhuyenMai' => 'required',
            'mucGiam' => 'required|numeric|min:0',
            'loaiGiam' => 'required|in:Phan tram,Tien mat',
            'ngayBatDau' => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayBatDau',
            'maTour' => 'nullable|exists:tour,maTour',
            'apDungTatCaTour' => 'boolean',
        ]);

        $data = $request->all();
        if ($request->has('apDungTatCaTour') && $request->apDungTatCaTour) {
            $data['maTour'] = null;
        }

        $khuyenMai->update($data);
        return redirect()->route('admin.khuyenmai.index')->with('success', 'Cập nhật khuyến mãi thành công!');
    }

    public function destroy($id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        $khuyenMai->delete();
        return redirect()->route('admin.khuyenmai.index')->with('success', 'Xóa khuyến mãi thành công!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        $request->validate(['tinhTrang' => 'required|in:0,1']);
        $khuyenMai->update(['tinhTrang' => $request->tinhTrang]);
        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
    }
}