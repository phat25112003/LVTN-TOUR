<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachThamGia;
use App\Models\ChuyenTour;
use App\Models\Tour;
use App\Models\DatCho;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KhachThamGiaChuyenExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KhachChuyenController extends Controller
{
public function index()
{
    $admin = auth('admin')->user();

    $tours = Tour::with([
        'chuyentour' => function ($query) {
            $query->with([
                'datCho' => function ($q) {
                    $q->with(['khachThamGia', 'thanhtoan']);
                },
                'khachGhep' // ← load khách ghép riêng
            ]);
        }
    ])
    ->whereHas('chuyentour', function ($q) {
        $q->whereHas('datCho.khachThamGia') // có khách chính
          ->orWhereHas('khachGhep'); // có khách ghép
    })
    ->orderByDesc('maTour')
    ->get();

    return view('admin.khach-chuyen.index', compact('tours', 'admin'));
}
/**
 * Thêm khách ghép tour - maDatCho = NULL
 */
public function store(Request $request)
{
    $request->validate([
        'maChuyen'     => 'required|exists:chuyentour,maChuyen',
        'hoTenKhach'   => 'required|string|max:100',
        'tuoi'         => 'required|integer|min:1|max:120',
        'gioiTinh'     => ['required', Rule::in(['Nam', 'Nu'])],
        'luaChonPhong' => ['required', Rule::in(['Ghep', 'PhongDon'])],
    ]);

    // Khách ghép: maDatCho = null, maChuyen = chuyến được chọn
    KhachThamGia::create([
        'hoTenKhach'   => $request->hoTenKhach,
        'tuoi'         => $request->tuoi,
        'gioiTinh'     => $request->gioiTinh,
        'luaChonPhong' => $request->luaChonPhong,
        'maDatCho'     => null, // Khách ghép
        'maChuyen'     => $request->maChuyen, // Liên kết chuyến
    ]);

    // Tăng số lượng đã đặt
    $chuyen = ChuyenTour::find($request->maChuyen);
    $chuyen->increment('soLuongDaDat');

    return back()->with('success', 'Thêm khách ghép tour thành công!');
}

    /**
     * Cập nhật thông tin khách tham gia
     */
    public function update(Request $request, $maKhach)
    {
        $request->validate([
            'hoTenKhach'   => 'required|string|max:100',
            'tuoi'         => 'required|integer|min:1|max:120',
            'gioiTinh'     => ['required', Rule::in(['Nam', 'Nu'])],
            'luaChonPhong' => ['required', Rule::in(['Ghep', 'PhongDon'])],
        ], [
            'hoTenKhach.required'   => 'Họ tên không được để trống.',
            'tuoi.required'         => 'Tuổi không được để trống.',
            'tuoi.min'              => 'Tuổi phải lớn hơn 0.',
            'tuoi.max'              => 'Tuổi không hợp lý.',
            'gioiTinh.in'           => 'Giới tính không hợp lệ.',
            'luaChonPhong.in'       => 'Lựa chọn phòng không hợp lệ.',
        ]);

        $khach = KhachThamGia::findOrFail($maKhach);

        $khach->update($request->only([
            'hoTenKhach',
            'tuoi',
            'gioiTinh',
            'luaChonPhong',
        ]));

        return redirect()->back()->with('success', 'Cập nhật thông tin khách thành công!');
    }

    /**
     * Xóa khách tham gia
     */
public function destroy($maKhach)
{
    $khach = KhachThamGia::with('datCho.thanhtoan')->findOrFail($maKhach);

    // Chặn xóa nếu khách có mã đặt chỗ (thuộc đơn chính thức)
    if ($khach->maDatCho) {
        return back()->with('error', 'Không thể xóa khách này vì họ thuộc đơn đặt chỗ chính thức!');
    }

    // Nếu là khách ghép (maDatCho = null) → cho xóa
    $khach->delete();

    // Giảm số lượng đã đặt nếu có maChuyen
    if ($khach->maChuyen) {
        ChuyenTour::where('maChuyen', $khach->maChuyen)->decrement('soLuongDaDat');
    }

    return back()->with('success', 'Xóa khách thành công!');
}

public function export($maChuyen)
{
    $chuyen = ChuyenTour::findOrFail($maChuyen);
    $tour = Tour::find($chuyen->maTour);

    // Tên file Việt hóa, có dấu, ngày giờ
    $fileName = 'Danh_sach_khach_chuyen_' . '#000'.$maChuyen . '_' . Str::slug($tour->tieuDe) . '_' . now()->format('d-m-Y_H-i') . '.xlsx';

    return Excel::download(new KhachThamGiaChuyenExport($maChuyen), $fileName);
}
}