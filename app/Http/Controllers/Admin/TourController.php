<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
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
=======
use App\Models\HinhAnh;
use App\Models\LichTrinh;
use App\Models\DanhMuc;
use App\Models\Tour;
use App\Models\ChuyenTour;
use App\Models\GiaTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use App\Models\HuongDanVien;
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad

class TourController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $toursQuery = Tour::with('danhmuc');
        $danhmucs = DanhMuc::all();

<<<<<<< HEAD
        if ($request->has('maDanhMuc') && $request->maDanhMuc != '') {
=======
        if ($request->filled('maDanhMuc')) {
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
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
<<<<<<< HEAD
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
=======
            'thoiGian' => 'required|string|max:100',
            'moTa' => 'required|string',
            'diemDen' => 'required|string|max:255',
            'maDanhMuc' => 'nullable|exists:danhmuc,maDanhMuc',
            'hinhAnh.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
        ]);

        $tour = Tour::create([
            'tieuDe' => $request->tieuDe,
            'thoiGian' => $request->thoiGian,
            'moTa' => $request->moTa,
<<<<<<< HEAD
            'ngayBatDau' => $request->ngayBatDau,
            'ngayKetThuc' => $request->ngayKetThuc,
            'soLuong' => $request->soLuong,
            'giaNguoiLon' => $request->giaNguoiLon,
            'giaTreEm' => $request->giaTreEm,
            'diemDen' => $request->diemDen,
            'tinhTrang' => $request->tinhTrang,
=======
            'diemDen' => $request->diemDen,
            'hinhAnh' => 0,
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
            'maDanhMuc' => $request->maDanhMuc,
        ]);

        if ($request->hasFile('hinhAnh')) {
<<<<<<< HEAD
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
=======
            foreach ($request->file('hinhAnh') as $file) {
                $path = $file->store('images/tours', 'public');
                HinhAnh::create([
                    'moTa' => 'Hình tour: ' . $tour->tieuDe,
                    'duongDanHinh' => $path,
                    'maTour' => $tour->maTour,
                ]);
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
            }
        }

        $this->updateHinhAnhCount($tour->maTour);

<<<<<<< HEAD
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

=======
        $soNgay = $this->parseSoNgay($request->thoiGian);

        return redirect()
            ->route('admin.tours.createSchedule', $tour->maTour)
            ->with(['soNgay' => $soNgay])
            ->with('success', 'Tour đã lưu! Nhập lịch trình.');
    }

    public function show($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::findOrFail($maTour);
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
        $tour = Tour::with('danhmuc')->findOrFail($maTour);
        $danhmucs = DanhMuc::all();
        $hinhAnh = HinhAnh::where('maTour', $maTour)->get();
        return view('admin.tours.edit', compact('tour', 'danhmucs', 'hinhAnh', 'admin'));
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
            'hinhAnh.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'hinhAnhXoa.*' => 'nullable|exists:hinhanh,maHinhAnh',
        ]);

        $tour->update([
            'tieuDe' => $request->tieuDe,
            'thoiGian' => $request->thoiGian,
            'moTa' => $request->moTa,
            'diemDen' => $request->diemDen,
            'maDanhMuc' => $request->maDanhMuc,
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

>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
        return view('admin.tours.create_schedule', compact('tour', 'soNgay', 'admin'));
    }

    public function storeSchedule(Request $request, $maTour)
    {
<<<<<<< HEAD
        $tour = Tour::findOrFail($maTour);

=======
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'sang.*' => 'nullable|string',
            'trua.*' => 'nullable|string',
            'chieu.*' => 'nullable|string',
            'toi.*' => 'nullable|string',
        ]);

        $soNgay = count($request->huongDi);
<<<<<<< HEAD

=======
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
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

<<<<<<< HEAD
        return redirect()->route('admin.tours.index')->with('success', 'Thêm lịch trình thành công!');
=======
        $soChuyen = session('soChuyen', 1);

        return redirect()
            ->route('admin.tours.createTrips', $maTour)
            ->with('success', 'Lịch trình đã lưu! Nhập thông tin chuyến.');
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
    }

    public function editSchedule($maTour)
    {
        $admin = auth()->guard('admin')->user();
        $tour = Tour::findOrFail($maTour);
        $lichTrinh = LichTrinh::where('maTour', $maTour)->get();
<<<<<<< HEAD

        $soNgay = Carbon::parse($tour->ngayKetThuc)->diffInDays(Carbon::parse($tour->ngayBatDau)) + 1;

        return view('admin.tours.edit_schedule', compact('tour', 'lichTrinh', 'soNgay', 'admin'));
=======
        $soNgay = $this->parseSoNgay($tour->thoiGian); // DÙNG HÀM CŨ

        return view('admin.tours.edit_schedule', compact('tour', 'lichTrinh', 'soNgay','admin'));
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
    }

    public function updateSchedule(Request $request, $maTour)
    {
<<<<<<< HEAD
        $tour = Tour::findOrFail($maTour);

=======
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
        $request->validate([
            'huongDi.*' => 'required|string|max:255',
            'sang.*' => 'nullable|string',
            'trua.*' => 'nullable|string',
            'chieu.*' => 'nullable|string',
            'toi.*' => 'nullable|string',
        ]);

<<<<<<< HEAD
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
=======
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

        // === VALIDATE NGHIÊM NGẶT ===
        $request->validate([
            'ngayBatDau.*' => 'required|date|after_or_equal:today',
            'ngayKetThuc.*' => 'required|date',
            'diemKhoiHanh.*' => 'required|string|max:255',
            'soLuongToiDa.*' => 'required|integer|min:1',
            'tinhTrangChuyen.*' => 'required|in:HoatDong,NgungChay',
            'maHDV.*' => 'nullable|exists:huongdanvien,maHDV', // CHO PHÉP NULL
            'giaEmBe.*' => 'required|numeric|min:0',
            'giaTreEm.*' => 'required|numeric|min:0',
            'giaNguoiLon.*' => 'required|numeric|min:0',
        ]);

        // === LOG ĐỂ DEBUG ===
        Log::info('storeTrips - maHDV:', $request->maHDV ?? []);

        foreach ($request->ngayBatDau as $i => $ngayBatDau) {
            $ngayKetThuc = $request->ngayKetThuc[$i];
            $start = \Carbon\Carbon::parse($ngayBatDau);
            $endExpected = $start->copy()->addDays($soNgayTour - 1);

            if (!\Carbon\Carbon::parse($ngayKetThuc)->equalTo($endExpected)) {
                return back()->withErrors([
                    "ngayKetThuc.{$i}" => "Chuyến " . ($i + 1) . ": Ngày kết thúc phải là " . $endExpected->format('d/m/Y')
                ])->withInput();
            }

            // === LẤY maHDV AN TOÀN ===
            $maHDV = null;
            if (isset($request->maHDV[$i]) && !empty($request->maHDV[$i])) {
                $maHDV = $request->maHDV[$i];
            }

            $chuyen = ChuyenTour::create([
                'maTour' => $maTour,
                'ngayBatDau' => $ngayBatDau,
                'ngayKetThuc' => $ngayKetThuc,
                'diemKhoiHanh' => $request->diemKhoiHanh[$i],
                'maHDV' => $maHDV, // CHẮC CHẮN CÓ GIÁ TRỊ
                'phuongTien' => $request->phuongTien[$i] ?? null,
                'soLuongToiDa' => $request->soLuongToiDa[$i],
                'soLuongDaDat' => 0,
                'tinhTrangChuyen' => $request->tinhTrangChuyen[$i],
                'ghiChu' => $request->ghiChu[$i] ?? null,
            ]);

            GiaTour::create([
                'maChuyen' => $chuyen->maChuyen,
                'emBe' => $request->giaEmBe[$i],
                'treEm' => $request->giaTreEm[$i],
                'nguoiLon' => $request->giaNguoiLon[$i],
            ]);
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
        }

        return redirect()
            ->route('admin.tours.index')
<<<<<<< HEAD
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
=======
            ->with('success', 'Tạo chuyến thành công!');
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

        $request->validate([
            'maChuyen.*' => 'nullable|exists:chuyentour,maChuyen',
            'ngayBatDau.*' => 'required|date',
            'ngayKetThuc.*' => 'required|date',
            'diemKhoiHanh.*' => 'required|string|max:255',
            'soLuongToiDa.*' => 'required|integer|min:1',
            'maHDV.*' => 'nullable|exists:huongdanvien,maHDV',
            'giaEmBe.*' => 'required|numeric|min:0',
            'giaTreEm.*' => 'required|numeric|min:0',
            'giaNguoiLon.*' => 'required|numeric|min:0',
        ]);

        $existingIds = [];

        foreach ($request->maChuyen ?? [] as $index => $maChuyen) {
            $data = [
                'ngayBatDau' => $request->ngayBatDau[$index],
                'ngayKetThuc' => $request->ngayKetThuc[$index],
                'diemKhoiHanh' => $request->diemKhoiHanh[$index],
                'maHDV' => $request->maHDV[$index] ?? null,
                'phuongTien' => $request->phuongTien[$index] ?? null,
                'soLuongToiDa' => $request->soLuongToiDa[$index],
                'ghiChu' => $request->ghiChu[$index] ?? null,
            ];

            $start = \Carbon\Carbon::parse($data['ngayBatDau']);
            $endExpected = $start->copy()->addDays($soNgayTour - 1);
            if (!\Carbon\Carbon::parse($data['ngayKetThuc'])->equalTo($endExpected)) {
                return back()->withErrors([
                    "ngayKetThuc.{$index}" => "Chuyến " . ($index + 1) . ": Phải là " . $endExpected->format('d/m/Y')
                ])->withInput();
            }

            if ($maChuyen) {
                $chuyen = ChuyenTour::find($maChuyen);
                if ($chuyen && $chuyen->maTour == $maTour) {
                    $chuyen->update($data);
                    $existingIds[] = $maChuyen;

                    $chuyen->giaTour()->update([
                        'emBe' => $request->giaEmBe[$index],
                        'treEm' => $request->giaTreEm[$index],
                        'nguoiLon' => $request->giaNguoiLon[$index],
                    ]);
                }
            } else {
                $newChuyen = ChuyenTour::create(array_merge($data, [
                    'maTour' => $maTour,
                    'soLuongDaDat' => 0,
                    'tinhTrangChuyen' => 'HoatDong'
                ]));
                $existingIds[] = $newChuyen->maChuyen;

                GiaTour::create([
                    'maChuyen' => $newChuyen->maChuyen,
                    'emBe' => $request->giaEmBe[$index],
                    'treEm' => $request->giaTreEm[$index],
                    'nguoiLon' => $request->giaNguoiLon[$index],
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
                ]);
            }
        }

<<<<<<< HEAD
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
=======
        // XÓA CHUYẾN CŨ
        ChuyenTour::where('maTour', $maTour)
                  ->whereNotIn('maChuyen', $existingIds)
                  ->delete();

        return redirect()
            ->route('admin.tours.edit', $maTour)
            ->with('success', 'Cập nhật chuyến thành công!');
    }

    // public static function parseSoNgayStatic($thoiGian)
    // {
    //     $thoiGian = Str::lower($thoiGian);
    //     if (Str::contains($thoiGian, 'trong ngày')) return 1;
    //     if (preg_match('/(\d+)\s*ngày/', $thoiGian, $matches)) {
    //         return (int)$matches[1];
    //     }
    //     return 1;
    // }
}
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
