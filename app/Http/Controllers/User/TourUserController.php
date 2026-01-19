<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\DanhMuc;
use App\Models\DiaDiem;
use App\Models\LoaiDuLich;
class TourUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = now()->toDateString();

        // 1. Tour mới nhất theo maTour
        $latestTours = Tour::with(['hinhAnh','chuyentour','danhmuc'])
            ->orderBy('maTour','desc')
            ->take(6)
            ->get();

        // 2. Tour có chuyến sắp khởi hành
        $upcomingTours = Tour::whereHas('chuyentour', function($q) use ($today) {
            $q->where('ngayBatDau', '>=', $today);
        })
        ->with(['hinhanh', 'chuyentour' => function($q) use ($today){
            $q->where('ngayBatDau', '>=', $today)
              ->orderBy('ngayBatDau', 'asc');
        }])
        ->get()
        ->sortBy(function($tour){
            return optional($tour->chuyentour->first())->ngayBatDau;
        })
        ->values() // reset key
        ->take(8);


        $danhmucs = DanhMuc::with('diadiem')->get();
        $loaidulichs = LoaiDuLich::all();
        $diaDiems = DiaDiem::with('danhMuc')
        ->get()
        ->groupBy(fn ($dd) => $dd->danhMuc?->tenDanhMuc ?? 'Khác');

        return view('user.index', [
            'latestTours' => $latestTours,
            'upcomingTours' => $upcomingTours,
            'danhmucs' => $danhmucs,
            'loaidulichs' => $loaidulichs,
            'diaDiems'     => $diaDiems, 
        ]);

    }

    function normalize($str)
    {
        $str = mb_strtolower($str, 'UTF-8');

        // Bỏ dấu tiếng Việt
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);

        // Bỏ dấu chấm, dấu cách
        $str = str_replace(['.', ' '], '', $str);

        return $str;
    }
    
    private function abbr($str)
    {
        // Bỏ dấu
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $clean = mb_strtolower($clean, 'UTF-8');

        // Lấy ký tự đầu của mỗi từ
        preg_match_all('/\b([a-z])/', $clean, $matches);

        return implode('', $matches[1]); // ví dụ ho chi minh → hcm
    }


    public function search(Request $request)
    {
        $queryText = $request->input('query');
        $type      = $request->input('type');

        $keywordNorm = $this->normalize($queryText);
        $keywordAbbr = $this->abbr($queryText);

        /* =========================
        1️⃣ FILTER BẰNG SQL
        ========================= */
        $query = Tour::with(['danhmuc', 'loaidulich', 'chuyentour.giatour']);

        // 📍 ĐỊA ĐIỂM
        if ($request->filled('diaDiem')) {
            $query->where('diemDen', 'like', '%' . $request->diaDiem . '%');
        }

        // ⏱ THỜI GIAN (3n2d → "3 ngày 2 đêm")
        // ⏱ SỐ NGÀY (LINH HOẠT)
        if ($request->filled('soNgay')) {

            [$min, $max] = match ($request->soNgay) {
                '1-2' => [1, 2],
                '3-4' => [3, 4],
                '5-7' => [5, 7],
                '8+'  => [8, 99],
            };

            $query->where(function ($q) use ($min, $max) {
                $q->whereRaw("
                    CAST(SUBSTRING_INDEX(thoiGian, ' ', 1) AS UNSIGNED)
                    BETWEEN ? AND ?
                ", [$min, $max]);
            });
        }

        // 🧭 LOẠI DU LỊCH
        if ($request->filled('maLoai')) {
            $query->where('maLoai', $request->maLoai);
        }

        // 💰 GIÁ TRUNG BÌNH
        if ($request->filled('giaTB')) {

            [$giaTu, $giaDen] = match ($request->giaTB) {
                '1' => [0, 1_000_000],
                '2' => [1_000_000, 1_500_000],
                '3' => [1_500_000, 2_500_000],
                '4' => [2_500_000, PHP_INT_MAX],
            };

            $query->whereHas('chuyentour.giatour', function ($q) use ($giaTu, $giaDen) {
                $q->whereBetween('nguoiLon', [$giaTu, $giaDen]);
            });
        }


        $tours = $query->get();

        /* =========================
        2️⃣ SEARCH NÂNG CAO (PHP)
        ========================= */
        if ($queryText) {
            $tours = $tours->filter(function ($tour) use ($keywordNorm, $keywordAbbr, $type) {

                // CLICK LOẠI DU LỊCH
                if ($type === 'loaidulich') {
                    $typeNorm = $this->normalize(optional($tour->loaidulich)->tenLoai);
                    return str_contains($typeNorm, $keywordNorm);
                }

                $titleNorm = $this->normalize($tour->tieuDe);
                $titleAbbr = $this->abbr($tour->tieuDe);

                $cateNorm  = $this->normalize(optional($tour->danhmuc)->tenDanhMuc);
                $cateAbbr  = $this->abbr(optional($tour->danhmuc)->tenDanhMuc);

                $typeNorm  = $this->normalize(optional($tour->loaidulich)->tenLoai);
                $typeAbbr  = $this->abbr(optional($tour->loaidulich)->tenLoai);

                return
                    str_contains($titleNorm, $keywordNorm) ||
                    str_contains($cateNorm,  $keywordNorm) ||
                    str_contains($typeNorm,  $keywordNorm) ||
                    (
                        strlen($keywordAbbr) >= 2 && (
                            str_contains($titleAbbr, $keywordAbbr) ||
                            str_contains($cateAbbr,  $keywordAbbr) ||
                            str_contains($typeAbbr,  $keywordAbbr)
                        )
                    );
            });
        }

        $loaidulichs = LoaiDuLich::all();
        $diaDiems = DiaDiem::with('danhMuc')
        ->get()
        ->groupBy(fn ($dd) => $dd->danhMuc?->tenDanhMuc ?? 'Khác');

        return view('user.danhsachtour', [
            'tours'        => $tours,
            'query'        => $queryText,
            'loaidulichs'  => $loaidulichs,
            'diaDiems'     => $diaDiems, // ✅ THÊM DÒNG NÀY
        ]);



    }

}
