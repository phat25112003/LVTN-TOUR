<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class NguoiDungController extends Controller
{
    public function index()
    {
        $nguoiDungs = NguoiDung::all();
        return view('admin.nguoidung.index', compact('nguoiDungs'));
    }

    public function updateStatus(Request $request, $maNguoiDung)
    {
        $nguoiDung = NguoiDung::findOrFail($maNguoiDung);
        $request->validate([
            'tinhTrang' => 'required|in:0,1',
        ]);
        $nguoiDung->update(['tinhTrang' => $request->tinhTrang]);
        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
    }

    public function destroy($maNguoiDung)
    {
        $nguoiDung = NguoiDung::findOrFail($maNguoiDung);
        $nguoiDung->danhGia()->delete();
        $nguoiDung->datCho()->delete();
        $nguoiDung->lichSu()->delete();
        $nguoiDung->delete();
        return redirect()->back()->with('success', 'Xóa người dùng thành công!');
    }
}