<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\DatCho;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DatTourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.dattour');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($maTour)
    {
        $tour = Tour::find($maTour);
        return view('user.dattour', compact('tour'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'maTour' => 'required|exists:tour,maTour',
            'hoTen' => 'required|string|max:255',
            'email' => 'required|email',
            'ngayKhoiHanh' => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayKhoiHanh',
            'nguoiLon' => 'required|integer|min:1',
            'treEm' => 'required|integer|min:0',
            'phuongThucThanhToan' => 'required|in:momo,paypal,tại văn phòng',
        ]);

        DatCho::create([
            'maNguoiDung' => Auth::id(),
            'maTour' => $validated['maTour'],
            'tenNguoiDat' => $validated['hoTen'],
            'ngayDat' => Carbon::now(),
            'ngayKhoiHanh' => $validated['ngayKhoiHanh'],
            'ngayKetThuc' => $validated['ngayKetThuc'],
            'nguoiLon' => $validated['nguoiLon'],
            'treEm' => $validated['treEm'],
            'tongGia' => $request->tongGia, // hoặc tính toán từ giá tour
            'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
            'xacNhan' => 0, // mặc định chờ duyệt
        ]);

        return redirect()->route('dattour.create', ['maTour' => $validated['maTour']])
        ->with('success', 'Đặt tour thành công! Vui lòng chờ admin xác nhận.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
