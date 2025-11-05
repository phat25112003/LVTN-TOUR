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
        $tours = Tour::with('hinhanh','chuyentour')->get();
        $danhmucs = DanhMuc::all();
        return view('user.index', compact('tours', 'danhmucs'));
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
