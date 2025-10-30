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
        $tours = Tour::with('hinhanh')->get();
        $danhmucs = DanhMuc::all();
        return view('user.index', compact('tours', 'danhmucs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
