<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\HinhAnh;
use App\Models\DanhGia;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class TourPublicController extends Controller
{
    public function index()
    {
        $tours = Tour::where('tinhTrang', 1)->get(); // Chỉ hiển thị tour hoạt động
        return view('tours.index', compact('tours'));
    }

    public function show($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $hinhAnh = HinhAnh::where('tourid', $maTour)->get();
        $danhGia = DanhGia::where('maTour', $maTour)->get();
        $khuyenMai = KhuyenMai::where('maTour', $maTour)->get();

        return view('tours.show', compact('tour', 'hinhAnh', 'danhGia', 'khuyenMai'));
    }
}