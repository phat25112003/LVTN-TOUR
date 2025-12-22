<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\DanhMuc;
use App\Models\DiaDiem;
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

        return view('user.index', [
            'latestTours' => $latestTours,
            'upcomingTours' => $upcomingTours,
            'danhmucs' => $danhmucs
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
        $query = $request->input('query');

        $keywordNorm  = $this->normalize($query);
        $keywordAbbr  = $this->abbr($query);

        $tours = Tour::with('danhmuc')->get()->filter(function ($tour) use ($keywordNorm, $keywordAbbr) {

            // chuẩn hóa tiêu đề tour
            $titleNorm = $this->normalize($tour->tieuDe);
            $titleAbbr = $this->abbr($tour->tieuDe);

            // chuẩn hóa danh mục
            $cateNorm  = $this->normalize(optional($tour->danhmuc)->tenDanhMuc);
            $cateAbbr  = $this->abbr(optional($tour->danhmuc)->tenDanhMuc);

            return
                str_contains($titleNorm, $keywordNorm) ||
                str_contains($cateNorm, $keywordNorm) ||
                str_contains($titleAbbr, $keywordAbbr) ||
                str_contains($cateAbbr, $keywordAbbr);
        });

        return view('user.danhsachtour', compact('tours','query'));
    }


}
