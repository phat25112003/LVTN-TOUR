<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\DanhMuc;
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


        $danhmucs = DanhMuc::all();

        return view('user.index', [
            'latestTours' => $latestTours,
            'upcomingTours' => $upcomingTours,
            'danhmucs' => $danhmucs
        ]);

    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        $tours = Tour::query()
            ->where('tieuDe', 'LIKE', "%{$query}%")
            ->orWhereHas('danhmuc', function ($q) use ($query) {
                $q->where('tenDanhMuc', 'LIKE', "%{$query}%");
            })
            ->get();

        return view('user.danhsachtour', compact('tours', 'query'));
    }

}
