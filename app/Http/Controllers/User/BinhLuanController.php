<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BinhLuan;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    public function store(Request $request, $tour_id)
    {
        $request->validate([
            'noiDung' => 'required|string|max:1000',
            'danhGia' => 'required|integer|min:1|max:5'
        ]);

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
