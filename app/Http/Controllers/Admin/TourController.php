<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\DatCho;
use App\Models\HinhAnh;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use App\Models\LichSu;
use App\Models\ThanhToan;
use App\Models\Tour;
use App\Models\NguoiDung;
use App\Models\LichTrinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::all();
        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('admin.tours.create');
    }

    public function store(Request $request)
    {
        // 🔹 Validate dữ liệu
        $request->validate([
            'tieuDe'        => 'required|string|max:255',
            'moTa'          => 'required|string',
            'soLuong'       => 'required|integer',
            'giaNguoiLon'   => 'required|numeric|min:0',
            'giaTreEm'      => 'required|numeric|min:0',
            'thoiLuong'     => 'required|integer|min:1',
            'diemDen'       => 'required|string|max:255',
            'tinhTrang'     => 'required|boolean',

            // Mảng ảnh & từng ảnh bên trong
            'hinhAnh'       => 'nullable|array|max:7', // không vượt quá 7 ảnh
            'hinhAnh.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:51200', // 50MB mỗi ảnh  // tối đa 8MB mỗi ảnh, // 4MB mỗi ảnh
        ], [
            'hinhAnh.*.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'hinhAnh.*.max'   => 'Kích thước mỗi ảnh không được vượt quá 4MB.',
            'hinhAnh.max'     => 'Không được chọn quá 7 hình ảnh cho mỗi tour.',
        ]);

        // 🔹 Lưu tour
        $tour = Tour::create($request->except('hinhAnh'));

        // 🔹 Tạo dữ liệu mẫu liên quan (nếu cần)
        $nguoiDung = NguoiDung::first();
        if ($nguoiDung) {
            $datCho = DatCho::create([
                'maNguoiDung' => $nguoiDung->maNguoiDung,
                'maTour'      => $tour->maTour,
                'ngayDat'     => now(),
                'nguoiLon'    => 1,
                'treEm'       => 0,
                'tongGia'     => $tour->giaNguoiLon,
            ]);

            HoaDon::create([
                'maDatCho' => $datCho->maDatCho,
                'soTien'   => $tour->giaNguoiLon,
                'ngayTao'  => now(),
                'chiTiet'  => 'Hóa đơn cho tour ' . $tour->tieuDe,
            ]);

            KhuyenMai::create([
                'maTour'        => $tour->maTour,
                'tenKhuyenMai'  => 'Khuyến mãi mở đầu',
                'phanTramGiam'  => 10,
                'ngayBatDau'    => now(),
                'ngayKetThuc'   => now()->addMonth(),
                'moTa'          => 'Khuyến mãi cho tour mới ' . $tour->tieuDe,
            ]);
        }

        // 🔹 Upload hình ảnh (nếu có)
        if ($request->hasFile('hinhAnh')) {
            foreach ($request->file('hinhAnh') as $index => $file) {
                try {
                    $path = $file->store('images/tours', 'public');

                    HinhAnh::create([
                        'moTa'          => 'Hình ảnh tour ' . $tour->tieuDe,
                        'duongDanHinh'  => $path,
                        'tourid'        => $tour->maTour,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Lỗi upload hình ảnh thứ ' . ($index + 1) . ': ' . $e->getMessage());
                }
            }
        }

        // 🔹 Chuyển đến bước tạo lịch trình
        return redirect()
            ->route('admin.tours.createSchedule', $tour->maTour)
            ->with('thoiLuong', $tour->thoiLuong)
            ->with('success', 'Thêm tour thành công!');
    }


    public function createSchedule($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $thoiLuong = session('thoiLuong', $tour->thoiLuong);
        return view('admin.tours.create_schedule', compact('tour', 'thoiLuong'));
    }

    public function storeSchedule(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $thoiLuong = $tour->thoiLuong;

        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'noiDung.*' => 'required|string',
        ]);

        for ($i = 1; $i <= $thoiLuong; $i++) {
            LichTrinh::create([
                'maTour' => $maTour,
                'ngay' => $i,
                'huongDi' => $request->input("huongDi.$i"),
                'noiDung' => $request->input("noiDung.$i"),
            ]);
        }

        $this->updateHinhAnhCount($maTour);

        return redirect()->route('admin.tours.index')->with('success', 'Thêm tour và lịch trình thành công.');
    }

    public function show($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $hinhAnh = HinhAnh::where('tourid', $maTour)->get();
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        return view('admin.tours.show', compact('tour', 'hinhAnh', 'lichTrinh'));
    }

    public function edit($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $hinhAnh = HinhAnh::where('tourid', $maTour)->get();
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        return view('admin.tours.edit', compact('tour', 'hinhAnh', 'lichTrinh'));
    }

    public function update(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'tieuDe' => 'required|string|max:255',
            'moTa' => 'required|string',
            'soLuong' => 'required|integer',
            'giaNguoiLon' => 'required|numeric',
            'giaTreEm' => 'required|numeric',
            'thoiLuong' => 'required|integer|min:1',
            'diemDen' => 'required|string|max:255',
            'tinhTrang' => 'required|boolean',
            'hinhAnh.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'hinhAnhXoa' => 'array',
        ]);

        $tour->update([
            'tieuDe' => $request->tieuDe,
            'moTa' => $request->moTa,
            'soLuong' => $request->soLuong,
            'giaNguoiLon' => $request->giaNguoiLon,
            'giaTreEm' => $request->giaTreEm,
            'thoiLuong' => $request->thoiLuong,
            'diemDen' => $request->diemDen,
            'tinhTrang' => $request->tinhTrang,
        ]);

        if ($request->has('hinhAnhXoa')) {
            foreach ($request->hinhAnhXoa as $maHinhAnh) {
                $hinh = HinhAnh::find($maHinhAnh);
                if ($hinh && $hinh->tourid == $maTour) {
                    Storage::disk('public')->delete($hinh->duongDanHinh);
                    $hinh->delete();
                }
            }
            $this->updateHinhAnhCount($maTour);
        }

        if ($request->hasFile('hinhAnh')) {
            $existingImages = HinhAnh::where('tourid', $maTour)->count();
            $newImages = count($request->file('hinhAnh'));
            $totalImages = $existingImages - count($request->hinhAnhXoa ?? []) + $newImages;

            if ($totalImages > 7) {
                return back()->withErrors(['hinhAnh' => 'Tổng số hình ảnh không được vượt quá 7.']);
            }

            foreach ($request->file('hinhAnh') as $index => $file) {
                try {
                    $path = $file->store('images/tours', 'public');
                    HinhAnh::create([
                        'moTa' => 'Hình ảnh tour ' . $tour->tieuDe,
                        'duongDanHinh' => $path,
                        'tourid' => $maTour,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to upload hinhAnh[' . $index . ']: ' . $e->getMessage());
                    return back()->withErrors(['hinhAnh.' . $index => 'Tải lên hình ảnh thứ ' . ($index + 1) . ' thất bại: ' . $e->getMessage()]);
                }
            }
            $this->updateHinhAnhCount($maTour);
        }

        if ($request->has('updateSchedule')) {
            LichTrinh::where('maTour', $maTour)->delete();
            return redirect()->route('admin.tours.editSchedule', $maTour)->with('thoiLuong', $tour->thoiLuong);
        }

        return redirect()->route('admin.tours.index')->with('success', 'Cập nhật tour thành công.');
    }

    public function editSchedule($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $thoiLuong = session('thoiLuong', $tour->thoiLuong);
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        return view('admin.tours.edit_schedule', compact('tour', 'thoiLuong', 'lichTrinh'));
    }

    public function updateSchedule(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $thoiLuong = $tour->thoiLuong;

        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'noiDung.*' => 'required|string',
        ]);

        LichTrinh::where('maTour', $maTour)->delete();

        for ($i = 1; $i <= $thoiLuong; $i++) {
            LichTrinh::create([
                'maTour' => $maTour,
                'ngay' => $i,
                'huongDi' => $request->input("huongDi.$i"),
                'noiDung' => $request->input("noiDung.$i"),
            ]);
        }

        $this->updateHinhAnhCount($maTour);

        return redirect()->route('admin.tours.index')->with('success', 'Cập nhật tour và lịch trình thành công.');
    }

    public function destroy($maTour)
    {
        $tour = Tour::findOrFail($maTour);

        DanhGia::where('maTour', $maTour)->delete();
        KhuyenMai::where('maTour', $maTour)->delete();
        LichSu::where('maTour', $maTour)->delete();

        $datCho = DatCho::where('maTour', $maTour)->get();
        foreach ($datCho as $dc) {
            HoaDon::where('maDatCho', $dc->maDatCho)->delete();
            ThanhToan::where('maDatCho', $dc->maDatCho)->delete();
            $dc->delete();
        }

        $hinhAnh = HinhAnh::where('tourid', $maTour)->get();
        foreach ($hinhAnh as $hinh) {
            Storage::disk('public')->delete($hinh->duongDanHinh);
            $hinh->delete();
        }

        LichTrinh::where('maTour', $maTour)->delete();
        $tour->delete();

        return redirect()->route('admin.tours.index')->with('success', 'Xóa tour thành công.');
    }

    protected function updateHinhAnhCount($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $hinhAnhCount = HinhAnh::where('tourid', $maTour)->count();
        $tour->update(['hinhAnh' => $hinhAnhCount]);
    }
}