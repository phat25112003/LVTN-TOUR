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
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $toursQuery = Tour::with('danhmuc');
        $danhmucs = DanhMuc::all();

        if ($request->has('maDanhMuc') && $request->maDanhMuc != '') {
            $toursQuery->where('maDanhMuc', $request->maDanhMuc);
        }

        $tours = $toursQuery->get();
        return view('admin.tours.index', compact('tours', 'danhmucs', 'admin'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        $danhmucs = DanhMuc::all();
        return view('admin.tours.create', compact('danhmucs', 'admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieuDe' => 'required|string|max:255',
            'thoiGian' => 'required|string|max:255',
            'moTa' => 'required|string',
            'ngayBatDau' => 'required|date|after_or_equal:today',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayBatDau',
            'soLuong' => 'required|integer|min:1',
            'giaNguoiLon' => 'required|numeric|min:0',
            'giaTreEm' => 'required|numeric|min:0',
            'diemDen' => 'required|string|max:255',
            'tinhTrang' => 'required|boolean',
            'hinhAnh' => 'nullable|array',
            'hinhAnh.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'maDanhMuc' => 'nullable|exists:danhmuc,maDanhMuc',
        ]);

        $tour = Tour::create([
            'tieuDe' => $request->tieuDe,
            'thoiGian' => $request->thoiGian,
            'moTa' => $request->moTa,
            'ngayBatDau' => $request->ngayBatDau,
            'ngayKetThuc' => $request->ngayKetThuc,
            'soLuong' => $request->soLuong,
            'giaNguoiLon' => $request->giaNguoiLon,
            'giaTreEm' => $request->giaTreEm,
            'diemDen' => $request->diemDen,
            'tinhTrang' => $request->tinhTrang,
            'maDanhMuc' => $request->maDanhMuc,
        ]);

        if ($request->hasFile('hinhAnh')) {
            foreach ($request->file('hinhAnh') as $index => $file) {
                try {
                    $path = $file->store('images/tours', 'public');
                    HinhAnh::create([
                        'moTa' => 'Hình ảnh tour ' . $tour->tieuDe,
                        'duongDanHinh' => $path,
                        'maTour' => $tour->maTour,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Lỗi upload hình ảnh thứ ' . ($index + 1) . ': ' . $e->getMessage());
                }
            }
        }

        $this->updateHinhAnhCount($tour->maTour);

        $dateStart = strtotime($request->ngayBatDau);
        $dateEnd = strtotime($request->ngayKetThuc);
        $soNgay = max(1, floor(($dateEnd - $dateStart) / 86400) + 1);

        return redirect()
            ->route('admin.tours.createSchedule', $tour->maTour)
            ->with('soNgay', $soNgay)
            ->with('success', 'Thêm tour thành công! Hãy nhập lịch trình chi tiết.');
    }

    public function createSchedule($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::findOrFail($maTour);

        $soNgay = session('soNgay');
        if (!$soNgay && $tour->ngayBatDau && $tour->ngayKetThuc) {
            $soNgay = Carbon::parse($tour->ngayKetThuc)->diffInDays(Carbon::parse($tour->ngayBatDau)) + 1;
        }

        return view('admin.tours.create_schedule', compact('tour', 'soNgay', 'admin'));
    }

    public function storeSchedule(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'sang.*' => 'nullable|string',
            'trua.*' => 'nullable|string',
            'chieu.*' => 'nullable|string',
            'toi.*' => 'nullable|string',
        ]);

        $soNgay = count($request->huongDi);

        for ($i = 1; $i <= $soNgay; $i++) {
            LichTrinh::create([
                'maTour' => $maTour,
                'ngay' => $i,
                'huongDi' => $request->input("huongDi.{$i}"),
                'sang' => $request->input("sang.{$i}"),
                'trua' => $request->input("trua.{$i}"),
                'chieu' => $request->input("chieu.{$i}"),
                'toi' => $request->input("toi.{$i}"),
            ]);
        }

        return redirect()->route('admin.tours.index')->with('success', 'Thêm lịch trình thành công!');
    }

    public function editSchedule($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::findOrFail($maTour);
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();

        $soNgay = Carbon::parse($tour->ngayKetThuc)->diffInDays(Carbon::parse($tour->ngayBatDau)) + 1;

        return view('admin.tours.edit_schedule', compact('tour', 'lichTrinh', 'soNgay', 'admin'));
    }

    public function updateSchedule(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'sang.*' => 'nullable|string',
            'trua.*' => 'nullable|string',
            'chieu.*' => 'nullable|string',
            'toi.*' => 'nullable|string',
        ]);

        LichTrinh::where('maTour', $maTour)->delete();

        $soNgay = count($request->huongDi); // Sử dụng số ngày từ form

        $savedCount = 0;
        for ($i = 1; $i <= $soNgay; $i++) {
            $data = [
                'maTour' => $maTour,
                'ngay' => $i,
                'huongDi' => $request->input("huongDi.{$i}", ''),
                'sang' => $request->input("sang.{$i}", ''),
                'trua' => $request->input("trua.{$i}", ''),
                'chieu' => $request->input("chieu.{$i}", ''),
                'toi' => $request->input("toi.{$i}", ''),
            ];

            try {
                $lichTrinh = LichTrinh::create($data);
                $savedCount++;
            } catch (\Exception $e) {
                // Bỏ qua lỗi và tiếp tục, nhưng không lưu được sẽ giảm $savedCount
            }
        }

        if ($savedCount === 0) {
            return redirect()->back()->with('error', 'Không thể lưu lịch trình.');
        }

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Cập nhật lịch trình thành công.');
    }

    public function show($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::with('danhmuc')->findOrFail($maTour);
        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        return view('admin.tours.show', compact('tour', 'hinhAnh', 'lichTrinh' ,'admin'));
    }

    public function edit($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::with('danhmuc')->findOrFail($maTour);
        $danhmucs = DanhMuc::all();
        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        return view('admin.tours.edit', compact('tour', 'danhmucs', 'hinhAnh', 'lichTrinh', 'admin'));
    }

    public function update(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'tieuDe' => 'required|string|max:255',
            'thoiGian' => 'required|string|max:255',
            'moTa' => 'required|string',
            'ngayBatDau' => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayBatDau',
            'soLuong' => 'required|integer|min:1',
            'giaNguoiLon' => 'required|numeric|min:0',
            'giaTreEm' => 'required|numeric|min:0',
            'diemDen' => 'required|string|max:255',
            'tinhTrang' => 'required|boolean',
            'hinhAnh.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'hinhAnhXoa' => 'array',
            'maDanhMuc' => 'nullable|exists:danhmuc,maDanhMuc',
        ]);

        $tour->update([
            'tieuDe' => $request->tieuDe,
            'thoiGian' => $request->thoiGian,
            'moTa' => $request->moTa,
            'ngayBatDau' => $request->ngayBatDau,
            'ngayKetThuc' => $request->ngayKetThuc,
            'soLuong' => $request->soLuong,
            'giaNguoiLon' => $request->giaNguoiLon,
            'giaTreEm' => $request->giaTreEm,
            'diemDen' => $request->diemDen,
            'tinhTrang' => $request->tinhTrang,
            'maDanhMuc' => $request->maDanhMuc,
        ]);

        if ($request->has('hinhAnhXoa')) {
            foreach ($request->hinhAnhXoa as $maHinhAnh) {
                $hinh = HinhAnh::find($maHinhAnh);
                if ($hinh && $hinh->maTour == $maTour) {
                    Storage::disk('public')->delete($hinh->duongDanHinh);
                    $hinh->delete();
                }
            }
        }

        if ($request->hasFile('hinhAnh')) {
            foreach ($request->file('hinhAnh') as $file) {
                $path = $file->store('images/tours', 'public');
                HinhAnh::create([
                    'moTa' => 'Hình ảnh tour ' . $tour->tieuDe,
                    'duongDanHinh' => $path,
                    'maTour' => $maTour,
                ]);
            }
        }

        if ($request->has('updateSchedule')) {
            return redirect()
                ->route('admin.tours.editSchedule', $maTour)
                ->with('success', 'Cập nhật tour thành công. Bây giờ hãy chỉnh sửa lịch trình.');
        }

        $this->updateHinhAnhCount($maTour);

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
