<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BinhLuan;
use App\Models\DatCho;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    public function store(Request $request, $tour_id)
    {
        $request->validate([
            'noiDung' => 'required|string|max:1000',
            'danhGia' => 'required|integer|min:1|max:5'
        ]);

        $donHang = DatCho::where('maNguoiDung', Auth::id())
            ->where('maTour', $tour_id)
            ->where('xacNhan', 1)
            ->whereHas('chuyenTour', function ($query) {
                $query->whereDate('ngayKetThuc', '<', now());
            })
            ->first();

        if (!$donHang) {
            return back()->with('error', 'Bạn chỉ có thể bình luận sau khi hoàn thành tour.');
        }

        BinhLuan::create([
            'maNguoiDung' => Auth::id(),
            'maTour' => $tour_id,
            'noiDung' => $request->noiDung,
            'danhGia' => $request->danhGia,
            'ngayBinhLuan'=> now()  
        ]);

        return back()->with('success', 'Bình luận và đánh giá đã được gửi!');
    }
}
