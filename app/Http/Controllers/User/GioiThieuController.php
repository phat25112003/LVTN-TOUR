<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HuongDanVien;

class GioiThieuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hdv = HuongDanVien::all();
        return view('user.gioithieu', compact('hdv'));
    }
}
