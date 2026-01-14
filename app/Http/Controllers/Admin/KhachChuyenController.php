<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachThamGia;
use App\Models\Tour;
use App\Models\DatCho;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KhachChuyenController extends Controller
{
    /**
     * Hiển thị trang danh sách chuyến tour có khách tham gia
     */
    public function index()
    {
        $admin = auth('admin')->user();

        // Load dữ liệu tour + chuyến + đặt chỗ + khách + thanh toán để view dùng
        $tours = Tour::whereHas('chuyentour.datCho.khachThamGia')
            ->with([
                'chuyentour' => function ($query) {
                    $query->with([
                        'datCho' => function ($q) {
                            $q->with(['khachThamGia', 'thanhtoan']);
                        }
                    ]);
                }
            ])
            ->orderByDesc('maTour')
            ->get();

        return view('admin.khach-chuyen.index', compact('tours', 'admin'));
    }

    /**
     * Thêm khách mới vào chuyến (dùng cho modal thêm khách)
     */
    public function store(Request $request)
    {
        $request->validate([
            'maChuyen'     => 'required|exists:chuyentour,maChuyen',
            'hoTenKhach'   => 'required|string|max:100',
            'tuoi'         => 'required|integer|min:1|max:120',
            'gioiTinh'     => ['required', Rule::in(['Nam', 'Nu'])],
            'luaChonPhong' => ['required', Rule::in(['Ghep', 'PhongDon'])],
            'maVe'         => 'required|string|max:255|unique:khachthamgia,maVe',
        ], [
            'maChuyen.exists'       => 'Chuyến tour không tồn tại.',
            'hoTenKhach.required'   => 'Họ tên không được để trống.',
            'tuoi.required'         => 'Tuổi không được để trống.',
            'tuoi.min'              => 'Tuổi phải lớn hơn 0.',
            'tuoi.max'              => 'Tuổi không hợp lý.',
            'gioiTinh.in'           => 'Giới tính không hợp lệ.',
            'luaChonPhong.in'       => 'Lựa chọn phòng không hợp lệ.',
            'maVe.required'         => 'Mã vé không được để trống.',
            'maVe.unique'           => 'Mã vé đã tồn tại.',
        ]);

        // Lấy một đặt chỗ bất kỳ của chuyến này để gán khách (có thể cải tiến sau)
        $datCho = DatCho::where('maChuyen', $request->maChuyen)->first();

        if (!$datCho) {
            return back()->with('error', 'Không tìm thấy đặt chỗ nào cho chuyến này để thêm khách.');
        }

        KhachThamGia::create([
            'hoTenKhach'   => $request->hoTenKhach,
            'tuoi'         => $request->tuoi,
            'gioiTinh'     => $request->gioiTinh,
            'luaChonPhong' => $request->luaChonPhong,
            'maDatCho'     => $datCho->maDatCho,
            'maVe'         => $request->maVe,
        ]);

        return back()->with('success', 'Thêm khách mới thành công!');
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
            'maVe'         => 'required|string|max:255',
        ], [
            'hoTenKhach.required'   => 'Họ tên không được để trống.',
            'tuoi.required'         => 'Tuổi không được để trống.',
            'tuoi.min'              => 'Tuổi phải lớn hơn 0.',
            'tuoi.max'              => 'Tuổi không hợp lý.',
            'gioiTinh.in'           => 'Giới tính không hợp lệ.',
            'luaChonPhong.in'       => 'Lựa chọn phòng không hợp lệ.',
            'maVe.required'         => 'Mã vé không được để trống.',
        ]);

        $khach = KhachThamGia::findOrFail($maKhach);

        $khach->update($request->only([
            'hoTenKhach',
            'tuoi',
            'gioiTinh',
            'luaChonPhong',
            'maVe'
        ]));

        return redirect()->back()->with('success', 'Cập nhật thông tin khách thành công!');
    }

    /**
     * Xóa khách tham gia
     */
    public function destroy($maKhach)
    {
        $khach = KhachThamGia::findOrFail($maKhach);

        // Có thể thêm kiểm tra bổ sung nếu cần
        // Ví dụ: không cho xóa nếu chuyến đã khởi hành
        // if ($khach->datCho->chuyentour->ngayBatDau < now()) {
        //     return back()->with('error', 'Không thể xóa khách khi chuyến đã khởi hành.');
        // }

        $khach->delete();

        return redirect()->back()->with('success', 'Xóa khách thành công!');
    }
}