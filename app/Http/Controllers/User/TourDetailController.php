<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
class TourDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function show($maTour)
    {
<<<<<<< HEAD
        $tourdetail = Tour::with('lichtrinh','danhmuc','giatour','chuyentour')->findOrFail($maTour);
=======
        $tourdetail = Tour::with('lichtrinh','danhmuc')->findOrFail($maTour);
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
        return view('user.tourdetail', compact('tourdetail'));
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
}
