<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HinhAnh;
use App\Models\LichTrinh;
use App\Models\DanhMuc;
use App\Models\Tour;
use App\Models\ChuyenTour;
use App\Models\GiaTour;
use App\Models\BinhLuan;
use App\Models\LoaiDuLich;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use App\Models\HuongDanVien;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $toursQuery = Tour::with('danhmuc');

        // 1. Lọc theo danh mục 
        if ($request->filled('maDanhMuc')) {
            $toursQuery->where('maDanhMuc', $request->maDanhMuc);
        }

        // 2. Tìm kiếm theo từ khóa 
        if ($request->filled('search')) {
            $keyword = $request->search;

            $toursQuery->where(function ($q) use ($keyword) {
                $q->where('diemDen', 'LIKE', "%{$keyword}%")
                ->orWhere('tieuDe', 'LIKE', "%{$keyword}%"); // tùy chọn
            });
        }

        $tours = $toursQuery->orderBy('maTour', 'desc')
                            ->paginate(15)
                            ->withQueryString();
        
        $danhmucs = DanhMuc::all();

        return view('admin.tours.index', compact('tours', 'danhmucs', 'admin'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        $danhmucs = DanhMuc::all();
        $loaiDuLichs = LoaiDuLich::orderBy('tenLoai')->get(); // Load loại du lịch

        return view('admin.tours.create', compact('danhmucs', 'loaiDuLichs', 'admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieuDe' => 'required|string|max:255',
            'thoiGian' => 'required|string|max:100',
            'moTa' => 'required|string',
            'diemDen' => 'required|string|max:255',
            'maDanhMuc' => 'nullable|exists:danhmuc,maDanhMuc',
            'maLoai' => 'nullable|exists:loaidulich,maLoai', 
            'giaPhongDon'  => 'required|numeric|min:0',
            'hinhAnh.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $tour = Tour::create([
            'tieuDe' => $request->tieuDe,
            'thoiGian' => $request->thoiGian,
            'moTa' => $request->moTa,
            'diemDen' => $request->diemDen,
            'hinhAnh' => 0,
            'maDanhMuc' => $request->maDanhMuc,
            'maLoai' => $request->maLoai, 
            'giaPhongDon' => $request->giaPhongDon,
        ]);

        $this->updateHinhAnhCount($tour->maTour);

        $soNgay = $this->parseSoNgay($request->thoiGian);

        return redirect()
            ->route('admin.tours.createSchedule', $tour->maTour)
            ->with(['soNgay' => $soNgay])
            ->with('success', 'Tour đã lưu! Nhập lịch trình.');
    }

    public function show($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::with(['danhmuc', 'loaidulich'])->findOrFail($maTour); // ← Thêm 'loaidulich'
        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
        $lichTrinh = LichTrinh::where('maTour', $maTour)->orderBy('ngay')->get();
        $chuyenTours = ChuyenTour::with('huongDanVien')
                             ->where('maTour', $maTour)
                             ->orderBy('ngayBatDau')
                             ->get();

        return view('admin.tours.show', compact('tour', 'hinhAnh', 'lichTrinh', 'chuyenTours','admin'));
    }

    public function edit($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::with(['danhmuc', 'loaidulich'])->findOrFail($maTour); // ← Eager load loaidulich
        $danhmucs = DanhMuc::all();
        $loaiDuLichs = LoaiDuLich::orderBy('tenLoai')->get(); // ← Load danh sách loại
        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();

        return view('admin.tours.edit', compact('tour', 'danhmucs', 'loaiDuLichs', 'hinhAnh', 'admin'));
    }

    public function update(Request $request, $maTour)
    {
        $tour = Tour::findOrFail($maTour);

        $request->validate([
            'tieuDe' => 'required|string|max:255',
            'thoiGian' => 'required|string|max:100',
            'moTa' => 'required|string',
            'diemDen' => 'required|string|max:255',
            'maDanhMuc' => 'nullable|exists:danhmuc,maDanhMuc',
            'maLoai' => 'nullable|exists:loaidulich,maLoai', 
            'giaPhongDon' => 'required|numeric|min:0',
            'hinhAnh.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'hinhAnhXoa.*' => 'nullable|exists:hinhanh,maHinhAnh',
        ]);

        $tour->update([
            'tieuDe' => $request->tieuDe,
            'thoiGian' => $request->thoiGian,
            'moTa' => $request->moTa,
            'diemDen' => $request->diemDen,
            'maDanhMuc' => $request->maDanhMuc,
            'maLoai' => $request->maLoai,
            'giaPhongDon' => $request->giaPhongDon,
        ]);

        // XÓA HÌNH ẢNH
        if ($request->hinhAnhXoa) {
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
                // LƯU VÀO storage/app/public/images/tours
                $path = $file->store('images/tours', 'public');

                HinhAnh::create([
                    'moTa' => 'Hình tour: ' . $tour->tieuDe,
                    'duongDanHinh' => $path,
                    'maTour' => $tour->maTour,
                ]);
            }
        }

        $this->updateHinhAnhCount($maTour);

        return redirect()
            ->route('admin.tours.show', $maTour)
            ->with('success', 'Cập nhật tour thành công!');
    }

    public function destroy($maTour)
    {
        $tour = Tour::findOrFail($maTour);


        ChuyenTour::where('maTour', $maTour)->delete();


        LichTrinh::where('maTour', $maTour)->delete();

        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
        foreach ($hinhAnh as $hinh) {
            Storage::disk('public')->delete($hinh->duongDanHinh);
            $hinh->delete();
        }

        BinhLuan::where('maTour', $maTour)->delete();

        // 4. XÓA CÁC BẢNG KHÁC (nếu có)
        // DanhGia::where('maTour', $maTour)->delete();
        // DatCho::where('maTour', $maTour)->delete();
        // KhuyenMai::where('maTour', $maTour)->delete();

        // 5. CUỐI CÙNG: XÓA TOUR
        $tour->delete();

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Xóa tour thành công!');
    }

    protected function updateHinhAnhCount($maTour)
    {
        $count = HinhAnh::where('maTour', $maTour)->count();
        Tour::where('maTour', $maTour)->update(['hinhAnh' => $count]);
    }

    // === Hàm parse số ngày từ chuỗi thoiGian ===
    private function parseSoNgay($thoiGian)
    {
        // Ví dụ: "3 ngày 2 đêm", "Trong ngày", "5 Ngày 4 Đêm"
        $thoiGian = Str::lower($thoiGian);

        if (Str::contains($thoiGian, 'trong ngày')) {
            return 1;
        }

        if (preg_match('/(\d+)\s*ngày/', $thoiGian, $matches)) {
            return (int)$matches[1];
        }

        return 1;
    }

    // === Tạo lịch trình ===
    public function createSchedule($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $hasSchedule = LichTrinh::where('maTour', $maTour)->exists();

        if ($hasSchedule) {
            return redirect()->route('admin.tours.show', $maTour)
                ->with('info', 'Lịch trình đã được tạo trước đó.');
        }

        $soNgay = session('soNgay', $this->parseSoNgay($tour->thoiGian));
        $admin = auth()->guard('admin')->user();

        return view('admin.tours.create_schedule', compact('tour', 'soNgay', 'admin'));
    }

    public function storeSchedule(Request $request, $maTour)
    {
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

        $soChuyen = session('soChuyen', 1);

        return redirect()
            ->route('admin.tours.createTrips', $maTour)
            ->with('success', 'Lịch trình đã lưu! Nhập thông tin chuyến.');
    }

    public function editSchedule($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::findOrFail($maTour);
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
        $soNgay = $this->parseSoNgay($tour->thoiGian); // DÙNG HÀM CŨ

        return view('admin.tours.edit_schedule', compact('tour', 'lichTrinh', 'soNgay','admin'));
    }

    public function updateSchedule(Request $request, $maTour)
    {
        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'sang.*' => 'nullable|string',
            'trua.*' => 'nullable|string',
            'chieu.*' => 'nullable|string',
            'toi.*' => 'nullable|string',
        ]);

        // XÓA lịch trình cũ
        LichTrinh::where('maTour', $maTour)->delete();

        // LƯU lịch trình mới
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

        return redirect()
            ->route('admin.tours.show', $maTour)
            ->with('success', 'Cập nhật lịch trình thành công!');
    }

    public function createTrips($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        
        // SỬA: Chỉ lấy HDV Hoạt động
        $huongDanViens = HuongDanVien::where('trangThai', 'HoatDong')
                                    ->orderBy('hoTen')
                                    ->get();
        
        $admin = auth()->guard('admin')->user();

        return view('admin.tours.create_trips', compact('tour', 'huongDanViens', 'admin'));
    }
public function storeTrips(Request $request, $maTour)
{
    $tour = Tour::findOrFail($maTour);
    $soNgayTour = $this->parseSoNgay($tour->thoiGian);
    $today = \Carbon\Carbon::today();

    $request->validate([
        'ngayBatDau.*' => 'required|date',
        'ngayKetThuc.*' => 'required|date',
        'diemKhoiHanh.*' => 'required|string|max:255',
        'soLuongToiDa.*' => 'required|integer|min:1',
        'tinhTrangChuyen.*' => 'required|in:ChuaDuKhach,DuKhach,DaKhoiHanh,Huy',
        'maHDV.*' => 'nullable|exists:huongdanvien,maHDV',
        'giaNguoiLon.*' => 'required|numeric|min:0',
        'so_khach_toi_thieu.*' => 'required|integer|min:1|lte:soLuongToiDa.*',
    ]);

    // Lấy tất cả ngày bắt đầu hiện có của tour này để kiểm tra trùng
    $existingDates = ChuyenTour::where('maTour', $maTour)
        ->pluck('ngayBatDau')
        ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
        ->toArray();

    foreach ($request->ngayBatDau as $i => $ngayBatDauStr) {
        $startDate = \Carbon\Carbon::parse($ngayBatDauStr);

        // 1. Không cho ngày bắt đầu trong quá khứ
        if ($startDate->lt($today)) {
            return back()->withErrors([
                "ngayBatDau.{$i}" => "Chuyến " . ($i + 1) . ": Ngày bắt đầu không được trong quá khứ!"
            ])->withInput();
        }

        // 2. Kiểm tra trùng ngày bắt đầu
        $formattedDate = $startDate->format('Y-m-d');
        if (in_array($formattedDate, $existingDates)) {
            return back()->withErrors([
                "ngayBatDau.{$i}" => "Chuyến " . ($i + 1) . ": Ngày bắt đầu đã trùng với một chuyến khác!"
            ])->withInput();
        }

        // Thêm vào mảng để kiểm tra trùng trong cùng form (nếu thêm nhiều chuyến cùng lúc)
        $existingDates[] = $formattedDate;

        // Kiểm tra ngày kết thúc đúng với số ngày tour
        $ngayKetThuc = $request->ngayKetThuc[$i];
        $endExpected = $startDate->copy()->addDays($soNgayTour - 1);
        if (!\Carbon\Carbon::parse($ngayKetThuc)->equalTo($endExpected)) {
            return back()->withErrors([
                "ngayKetThuc.{$i}" => "Chuyến " . ($i + 1) . ": Ngày kết thúc phải là " . $endExpected->format('d/m/Y')
            ])->withInput();
        }

        $giaNguoiLon = $request->giaNguoiLon[$i];
        $giaTreEm = round($giaNguoiLon * 0.7 / 10000) * 10000;
        $giaEmBe = 0;

        $chuyen = ChuyenTour::create([
            'maTour' => $maTour,
            'ngayBatDau' => $ngayBatDauStr,
            'ngayKetThuc' => $ngayKetThuc,
            'diemKhoiHanh' => $request->diemKhoiHanh[$i],
            'maHDV' => $request->maHDV[$i] ?? null,
            'phuongTien' => $request->phuongTien[$i] ?? null,
            'soLuongToiDa' => $request->soLuongToiDa[$i],
            'soLuongDaDat' => 0,
            'tinhTrangChuyen' => $request->tinhTrangChuyen[$i] ?? 'ChuaDuKhach',
            'ghiChu' => $request->ghiChu[$i] ?? null,
            'so_khach_toi_thieu' => $request->so_khach_toi_thieu[$i],
        ]);

        GiaTour::create([
            'maChuyen' => $chuyen->maChuyen,
            'emBe' => $giaEmBe,
            'treEm' => $giaTreEm,
            'nguoiLon' => $giaNguoiLon,
        ]);
    }

    return redirect()->route('admin.tours.index')->with('success', 'Tạo chuyến thành công!');
}

    public function editTrips($maTour)
    {
        $tour = Tour::findOrFail($maTour);
        $chuyenTours = ChuyenTour::with('giaTour', 'huongDanVien')
                                ->where('maTour', $maTour)
                                ->orderBy('ngayBatDau')
                                ->get();
        
        // SỬA: Chỉ lấy HDV Hoạt động
        $huongDanViens = HuongDanVien::where('trangThai', 'HoatDong')
                                    ->orderBy('hoTen')
                                    ->get();
        
        $admin = auth()->guard('admin')->user();

        return view('admin.tours.edit_trips', compact('tour', 'chuyenTours', 'huongDanViens', 'admin'));
    }

public function updateTrips(Request $request, $maTour)
{
    $tour = Tour::findOrFail($maTour);
    $soNgayTour = $this->parseSoNgay($tour->thoiGian);
    $today = \Carbon\Carbon::today();

    $request->validate([
        'maChuyen.*' => 'nullable|exists:chuyentour,maChuyen',
        'ngayBatDau.*' => 'required|date',
        'ngayKetThuc.*' => 'required|date',
        'diemKhoiHanh.*' => 'required|string|max:255',
        'soLuongToiDa.*' => 'required|integer|min:1',
        'maHDV.*' => 'nullable|exists:huongdanvien,maHDV',
        'giaNguoiLon.*' => 'required|numeric|min:0',
        'so_khach_toi_thieu.*' => 'required|integer|min:1|lte:soLuongToiDa.*',
        'tinhTrangChuyen.*' => ['required', 'in:ChuaDuKhach,DuKhach,DaKhoiHanh,Huy'],
    ]);

    $existingIds = [];

    // Lấy tất cả ngày bắt đầu hiện có (trừ các chuyến đang được sửa trong form)
    $currentChuyenIds = array_filter($request->maChuyen ?? []);
    $existingDates = ChuyenTour::where('maTour', $maTour)
        ->whereNotIn('maChuyen', $currentChuyenIds)
        ->pluck('ngayBatDau')
        ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
        ->toArray();

    foreach ($request->ngayBatDau as $index => $ngayBatDauStr) {
        $startDate = \Carbon\Carbon::parse($ngayBatDauStr);

        // 1. Không cho ngày bắt đầu trong quá khứ
        if (empty($request->maChuyen[$index]) && $startDate->lt($today)) {
            return back()->withErrors([
                "ngayBatDau.{$index}" => "Chuyến " . ($index + 1) . ": Ngày bắt đầu không được trong quá khứ!"
            ])->withInput();
        }

        $formattedDate = $startDate->format('Y-m-d');

        // Nếu là chuyến cũ → loại bỏ ngày cũ khỏi danh sách kiểm tra trùng
        if (!empty($request->maChuyen[$index])) {
            $oldDate = ChuyenTour::where('maChuyen', $request->maChuyen[$index])
                ->value('ngayBatDau');
            if ($oldDate) {
                $oldFormatted = \Carbon\Carbon::parse($oldDate)->format('Y-m-d');
                $existingDates = array_diff($existingDates, [$oldFormatted]);
            }
        }

        // 2. Kiểm tra trùng ngày
        if (in_array($formattedDate, $existingDates)) {
            return back()->withErrors([
                "ngayBatDau.{$index}" => "Chuyến " . ($index + 1) . ": Ngày bắt đầu đã trùng với chuyến khác!"
            ])->withInput();
        }

        // Thêm vào để kiểm tra trùng trong cùng form
        $existingDates[] = $formattedDate;

        // Kiểm tra ngày kết thúc
        $endExpected = $startDate->copy()->addDays($soNgayTour - 1);
        if (!\Carbon\Carbon::parse($request->ngayKetThuc[$index])->equalTo($endExpected)) {
            return back()->withErrors([
                "ngayKetThuc.{$index}" => "Chuyến " . ($index + 1) . ": Ngày kết thúc phải là " . $endExpected->format('d/m/Y')
            ])->withInput();
        }

        $data = [
            'ngayBatDau' => $ngayBatDauStr,
            'ngayKetThuc' => $request->ngayKetThuc[$index],
            'diemKhoiHanh' => $request->diemKhoiHanh[$index],
            'maHDV' => $request->maHDV[$index] ?? null,
            'phuongTien' => $request->phuongTien[$index] ?? null,
            'soLuongToiDa' => $request->soLuongToiDa[$index],
            'ghiChu' => $request->ghiChu[$index] ?? null,
            'so_khach_toi_thieu' => $request->so_khach_toi_thieu[$index] ?? 10,
            'tinhTrangChuyen' => $request->tinhTrangChuyen[$index] ?? 'ChuaDuKhach',
        ];

        $giaNguoiLon = $request->giaNguoiLon[$index];
        $giaTreEm = round($giaNguoiLon * 0.7 / 10000) * 10000;
        $giaEmBe = 0;

        if (!empty($request->maChuyen[$index])) {
            $chuyen = ChuyenTour::findOrFail($request->maChuyen[$index]);
            if ($chuyen->maTour == $maTour) {
                $chuyen->update($data);
                $existingIds[] = $chuyen->maChuyen;

                $chuyen->giaTour()->update([
                    'emBe' => $giaEmBe,
                    'treEm' => $giaTreEm,
                    'nguoiLon' => $giaNguoiLon,
                ]);
            }
        } else {
            $newChuyen = ChuyenTour::create(array_merge($data, [
                'maTour' => $maTour,
                'soLuongDaDat' => 0,
            ]));
            $existingIds[] = $newChuyen->maChuyen;

            GiaTour::create([
                'maChuyen' => $newChuyen->maChuyen,
                'emBe' => $giaEmBe,
                'treEm' => $giaTreEm,
                'nguoiLon' => $giaNguoiLon,
            ]);
        }
    }

    // Xóa chuyến bị bỏ khỏi form
    ChuyenTour::where('maTour', $maTour)
        ->whereNotIn('maChuyen', $existingIds)
        ->delete();

    return redirect()->route('admin.tours.edit', $maTour)->with('success', 'Cập nhật chuyến thành công!');
}
}
