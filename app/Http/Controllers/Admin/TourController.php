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
        // Validate dữ liệu nhập
        $request->validate([
            'tieuDe'        => 'required|string|max:255',
            'thoiGian'      => 'required|string|max:255',
            'moTa'          => 'required|string',
            'ngayBatDau'    => 'required|date|after_or_equal:today',
            'ngayKetThuc'   => 'required|date|after_or_equal:ngayBatDau',
            'soLuong'       => 'required|integer|min:1',
            'giaNguoiLon'   => 'required|numeric|min:0',
            'giaTreEm'      => 'required|numeric|min:0',
            'diemDen'       => 'required|string|max:255',
            'tinhTrang'     => 'required|boolean',
            'hinhAnh'       => 'nullable|array',
            'hinhAnh.*'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:51200',
        ]);

        // Lưu tour mới
        $tour = Tour::create([
            'tieuDe'      => $request->tieuDe,
            'thoiGian'    => $request->thoiGian,
            'moTa'        => $request->moTa,
            'ngayBatDau'  => $request->ngayBatDau,
            'ngayKetThuc' => $request->ngayKetThuc,
            'soLuong'     => $request->soLuong,
            'giaNguoiLon' => $request->giaNguoiLon,
            'giaTreEm'    => $request->giaTreEm,
            'diemDen'     => $request->diemDen,
            'tinhTrang'   => $request->tinhTrang,
        ]);

        // ✅ Upload hình ảnh (nếu có)
        if ($request->hasFile('hinhAnh')) {
            foreach ($request->file('hinhAnh') as $index => $file) {
                try {
                    $path = $file->store('images/tours', 'public');
                    HinhAnh::create([
                        'moTa'         => 'Hình ảnh tour ' . $tour->tieuDe,
                        'duongDanHinh' => $path,
                        'maTour'       => $tour->maTour,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Lỗi upload hình ảnh thứ ' . ($index + 1) . ': ' . $e->getMessage());
                }
            }
        }

        $this->updateHinhAnhCount($tour->maTour);

        // Tính số ngày giữa ngày bắt đầu & kết thúc
        $dateStart = strtotime($request->ngayBatDau);
        $dateEnd   = strtotime($request->ngayKetThuc);
        $soNgay    = max(1, floor(($dateEnd - $dateStart) / 86400) + 1); // ít nhất 1 ngày


        // Chuyển tới form tạo lịch trình
        return redirect()
            ->route('admin.tours.createSchedule', $tour->maTour)
            ->with('soNgay', $soNgay)
            ->with('success', 'Thêm tour thành công! Hãy nhập lịch trình chi tiết.');
    }

    public function createSchedule($maTour)
    {
        $tour = Tour::findOrFail($maTour);

        // Lấy số ngày từ session hoặc tính lại
        $soNgay = session('soNgay');
        if (!$soNgay && $tour->ngayBatDau && $tour->ngayKetThuc) {
            $dateStart = strtotime($tour->ngayBatDau);
            $dateEnd   = strtotime($tour->ngayKetThuc);
            $soNgay    = max(1, floor(($dateEnd - $dateStart) / 86400) + 1);
        }

        return view('admin.tours.create_schedule', compact('tour', 'soNgay'));
    }

    public function storeSchedule(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'noiDung.*' => 'required|string',
        ]);

        // Đếm số lịch trình
        $tongNgay = count($request->huongDi);

        for ($i = 1; $i <= $tongNgay; $i++) {
            LichTrinh::create([
                'maTour'  => $maTour,
                'ngay'    => $i,
                'huongDi' => $request->input("huongDi.$i"),
                'noiDung' => $request->input("noiDung.$i"),
            ]);
        }

        return redirect()->route('admin.tours.index')->with('success', 'Thêm tour và lịch trình thành công.');
    }

    public function editSchedule($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();

        // Tính số ngày dựa trên ngày bắt đầu và kết thúc
        $soNgay = \Carbon\Carbon::parse($tour->ngayBatDau)->diffInDays(\Carbon\Carbon::parse($tour->ngayKetThuc)) + 1;

        return view('admin.tours.edit_schedule', compact('tour', 'lichTrinh', 'soNgay'));
    }

    public function updateSchedule(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'noiDung.*' => 'required|string',
        ]);

        // Xóa lịch trình cũ trước khi cập nhật
        LichTrinh::where('maTour', $maTour)->delete();

        $ngayBatDau = \Carbon\Carbon::parse($tour->ngayBatDau);
        $ngayKetThuc = \Carbon\Carbon::parse($tour->ngayKetThuc);
        $soNgay = $ngayBatDau->diffInDays($ngayKetThuc) + 1;

        // Thêm lại lịch trình mới
        for ($i = 1; $i <= $soNgay; $i++) {
            LichTrinh::create([
                'maTour' => $maTour,
                'ngay' => $i,
                'huongDi' => $request->input("huongDi.$i"),
                'noiDung' => $request->input("noiDung.$i"),
            ]);
        }

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Cập nhật lịch trình thành công.');
    }


    public function show($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        return view('admin.tours.show', compact('tour', 'hinhAnh', 'lichTrinh'));
    }

    public function edit($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        return view('admin.tours.edit', compact('tour', 'hinhAnh', 'lichTrinh'));
    }

    public function update(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'tieuDe'      => 'required|string|max:255',
            'thoiGian'    => 'required|string|max:255',
            'moTa'        => 'required|string',
            'ngayBatDau'  => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayBatDau',
            'soLuong'     => 'required|integer|min:1',
            'giaNguoiLon' => 'required|numeric|min:0',
            'giaTreEm'    => 'required|numeric|min:0',
            'diemDen'     => 'required|string|max:255',
            'tinhTrang'   => 'required|boolean',
            'hinhAnh.*'   => 'image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'hinhAnhXoa'  => 'array',
        ]);

        // Cập nhật thông tin tour
        $tour->update([
            'tieuDe'      => $request->tieuDe,
            'thoiGian'    => $request->thoiGian,
            'moTa'        => $request->moTa,
            'ngayBatDau'  => $request->ngayBatDau,
            'ngayKetThuc' => $request->ngayKetThuc,
            'soLuong'     => $request->soLuong,
            'giaNguoiLon' => $request->giaNguoiLon,
            'giaTreEm'    => $request->giaTreEm,
            'diemDen'     => $request->diemDen,
            'tinhTrang'   => $request->tinhTrang,
        ]);


        // Xử lý xóa hình ảnh nếu có
        if ($request->has('hinhAnhXoa')) {
            foreach ($request->hinhAnhXoa as $maHinhAnh) {
                $hinh = \App\Models\HinhAnh::find($maHinhAnh);
                if ($hinh && $hinh->maTour == $maTour) {
                    Storage::disk('public')->delete($hinh->duongDanHinh);
                    $hinh->delete();
                }
            }
        }


        // Thêm hình ảnh mới
        if ($request->hasFile('hinhAnh')) {
            foreach ($request->file('hinhAnh') as $file) {
                $path = $file->store('images/tours', 'public');
                \App\Models\HinhAnh::create([
                    'moTa' => 'Hình ảnh tour ' . $tour->tieuDe,
                    'duongDanHinh' => $path,
                    'maTour' => $maTour,
                ]);
            }
        }

        // Nếu bấm “Cập nhật lịch trình”, chuyển hướng tới trang sửa lịch trình
        if ($request->has('updateSchedule')) {
            return redirect()
                ->route('admin.tours.editSchedule', $maTour)
                ->with('success', 'Cập nhật tour thành công. Bây giờ hãy chỉnh sửa lịch trình.');
        }

        $this->updateHinhAnhCount($maTour);

        // Nếu không, quay lại danh sách tour
        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Cập nhật tour thành công.');
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

        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
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
            $hinhAnhCount = HinhAnh::where('maTour', $maTour)->count();
            $tour->update(['hinhAnh' => $hinhAnhCount]);
        }

}
