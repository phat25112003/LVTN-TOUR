<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\Tour;
use App\Models\DanhMuc;
use App\Models\ChuyenTour;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        $khuyenMais = KhuyenMai::orderByDesc('created_at')->get();

        return view('admin.khuyenmai.index', compact('khuyenMais', 'admin'));
    }

    public function create()
    {
        $admin = auth('admin')->user();
        $tours = Tour::select('maTour', 'tieuDe')->orderBy('tieuDe')->get();
        $danhmucs = DanhMuc::select('maDanhMuc', 'tenDanhMuc')->orderBy('tenDanhMuc')->get();
        $chuyens = ChuyenTour::with('tour:tour.maTour,tour.tieuDe')
            ->select('maChuyen', 'maTour', 'ngayBatDau')
            ->orderBy('ngayBatDau', 'desc')
            ->get();

        return view('admin.khuyenmai.create', compact('admin', 'tours', 'danhmucs', 'chuyens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'                  => 'required|string|max:20|unique:khuyenmai,code',
            'tenKM'                 => 'required|string|max:255',
            'loaiKM'                => 'required|in:percent,fixed,freeservice',
            'giaTri'                => 'required|numeric|min:0.01',
            'giaTriToiDa'           => 'nullable|numeric|min:0|required_if:loaiKM,percent',
            'apDung'                => 'required|in:tat_ca,danh_muc,tour_cu_the,chuyen_cu_the',
            
            // Chỉ yêu cầu khi chọn loại tương ứng
            'danhMucIDs'            => 'nullable|array|required_if:apDung,danh_muc',
            'danhMucIDs.*'          => 'exists:danhmuc,maDanhMuc',
            'tourIDs'               => 'nullable|array|required_if:apDung,tour_cu_the',
            'tourIDs.*'             => 'exists:tour,maTour',
            'chuyenIDs'             => 'nullable|array|required_if:apDung,chuyen_cu_the',
            'chuyenIDs.*'           => 'exists:chuyentour,maChuyen',

            'soTienToiThieu'        => 'nullable|numeric|min:0',
            'soLuongNguoiToiThieu'  => 'nullable|integer|min:1',
            'chiApDungNguoiLon'     => 'boolean',
            'ngayBatDau'            => 'required|date',
            'ngayKetThuc'           => 'required|date|after_or_equal:ngayBatDau',
            'soLuotSuDungToiDa'     => 'nullable|integer|min:1',
            'chiDungMoiNguoi1Lan'   => 'boolean',
            'trangThai'             => 'required|in:dang_chay,sap_chay,tam_dung,ket_thuc',
        ]);

        $data = $request->only([
            'code', 'tenKM', 'loaiKM', 'giaTri', 'giaTriToiDa', 'apDung',
            'soTienToiThieu', 'soLuongNguoiToiThieu', 'chiApDungNguoiLon',
            'ngayBatDau', 'ngayKetThuc', 'soLuotSuDungToiDa',
            'chiDungMoiNguoi1Lan', 'trangThai'
        ]);

        // Xử lý JSON đúng cách
        $data['danhMucIDs'] = $request->has('danhMucIDs') ? json_encode(array_map('intval', $request->danhMucIDs)) : null;
        $data['tourIDs']    = $request->has('tourIDs')    ? json_encode(array_map('intval', $request->tourIDs))    : null;
        $data['chuyenIDs']  = $request->has('chuyenIDs')  ? json_encode(array_map('intval', $request->chuyenIDs))  : null;

        KhuyenMai::create($data);

        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Thêm mã khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $admin = auth('admin')->user();
        $khuyenMai = KhuyenMai::findOrFail($id);

        $tours = Tour::select('maTour', 'tieuDe')->orderBy('tieuDe')->get();
        $danhmucs = DanhMuc::select('maDanhMuc', 'tenDanhMuc')->get();
        $chuyens = ChuyenTour::with('tour')->get();

        // Chuyển JSON → mảng để Blade dễ checked
        $khuyenMai->danhMucIDs = $khuyenMai->danhMucIDs ? json_decode($khuyenMai->danhMucIDs, true) : [];
        $khuyenMai->tourIDs    = $khuyenMai->tourIDs    ? json_decode($khuyenMai->tourIDs, true)    : [];
        $khuyenMai->chuyenIDs  = $khuyenMai->chuyenIDs  ? json_decode($khuyenMai->chuyenIDs, true)  : [];

        return view('admin.khuyenmai.edit', compact(
            'admin', 'khuyenMai', 'tours', 'danhmucs', 'chuyens'
        ));
    }

    public function update(Request $request, $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);

        $request->validate([
            'code'                  => ['required', 'string', 'max:20', Rule::unique('khuyenmai', 'code')->ignore($id, 'maKM')],
            'tenKM'                 => 'required|string|max:255',
            'loaiKM'                => 'required|in:percent,fixed,freeservice',
            'giaTri'                => 'required|numeric|min:0.01',
            'giaTriToiDa'           => 'nullable|numeric|min:0|required_if:loaiKM,percent',
            'apDung'                => 'required|in:tat_ca,danh_muc,tour_cu_the,chuyen_cu_the',
            'danhMucIDs'            => 'nullable|array|required_if:apDung,danh_muc',
            'danhMucIDs.*'          => 'exists:danhmuc,maDanhMuc',
            'tourIDs'               => 'nullable|array|required_if:apDung,tour_cu_the',
            'tourIDs.*'             => 'exists:tour,maTour',
            'chuyenIDs'             => 'nullable|array|required_if:apDung,chuyen_cu_the',
            'chuyenIDs.*'           => 'exists:chuyentour,maChuyen',
            'soTienToiThieu'        => 'nullable|numeric|min:0',
            'soLuongNguoiToiThieu'  => 'nullable|integer|min:1',
            'chiApDungNguoiLon'     => 'boolean',
            'ngayBatDau'            => 'required|date',
            'ngayKetThuc'           => 'required|date|after_or_equal:ngayBatDau',
            'soLuotSuDungToiDa'     => 'nullable|integer|min:1',
            'chiDungMoiNguoi1Lan'   => 'boolean',
            'trangThai'             => 'required|in:dang_chay,sap_chay,tam_dung,ket_thuc',
        ]);

        $data = $request->only([
            'code', 'tenKM', 'loaiKM', 'giaTri', 'giaTriToiDa', 'apDung',
            'soTienToiThieu', 'soLuongNguoiToiThieu', 'chiApDungNguoiLon',
            'ngayBatDau', 'ngayKetThuc', 'soLuotSuDungToiDa',
            'chiDungMoiNguoi1Lan', 'trangThai'
        ]);

        // Xử lý JSON an toàn
        $data['danhMucIDs'] = $request->has('danhMucIDs') ? json_encode(array_map('intval', $request->danhMucIDs)) : null;
        $data['tourIDs']    = $request->has('tourIDs')    ? json_encode(array_map('intval', $request->tourIDs))    : null;
        $data['chuyenIDs']  = $request->has('chuyenIDs')  ? json_encode(array_map('intval', $request->chuyenIDs))  : null;

        $khuyenMai->update($data);

        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Cập nhật mã khuyến mãi thành công!');
    }

    public function destroy($id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        
        // Nếu có đơn hàng đang dùng → không cho xóa (tùy bạn)
        // Hoặc soft delete nếu muốn
        $khuyenMai->delete();

        return redirect()->route('admin.khuyenmai.index')
            ->with('success', 'Xóa mã khuyến mãi thành công!');
    }

    public function toggleStatus($id)
    {
        $km = KhuyenMai::findOrFail($id);
        
        // Chỉ cho phép chuyển giữa đang chạy ↔ tạm dừng
        if (in_array($km->trangThai, ['dang_chay', 'tam_dung'])) {
            $km->trangThai = $km->trangThai === 'dang_chay' ? 'tam_dung' : 'dang_chay';
            $km->save();
        }

        return response()->json([
            'success' => true,
            'trangThai' => $km->trangThai,
            'text'     => $km->trangThai === 'dang_chay' ? 'Đang chạy' : 'Tạm dừng',
            'message'  => 'Cập nhật trạng thái thành công!'
        ]);
    }
}