<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhMuc;
class DanhMucController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $danhmucs = DanhMuc::all();
        return view('admin.danhmuc.index', compact('danhmucs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.danhmuc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $danhmuc = new DanhMuc();
        $danhmuc->tenDanhMuc = $request->tenDanhMuc;
        $danhmuc->save();
        return redirect()->route('admin.danhmuc.index')->with('success', 'Danh mục đã được tạo thành công.');
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
        return view('admin.danhmuc.edit', ['danhmuc' => DanhMuc::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $maDanhMuc)
    {
        $danhmuc = DanhMuc::find($maDanhMuc);
        if ($danhmuc) {
            $danhmuc->tenDanhMuc = $request->tenDanhMuc;
            $danhmuc->save();
            return redirect()->route('admin.danhmuc.index')->with('success', 'Danh mục đã được cập nhật thành công.');
        } else {
            return redirect()->route('admin.danhmuc.index')->with('error', 'Danh mục không tồn tại.');
        }   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($maDanhMuc)
    {
        $danhmuc = DanhMuc::find($maDanhMuc);
        if ($danhmuc) {
            $danhmuc->delete();
            return redirect()->route('admin.danhmuc.index')->with('success', 'Danh mục đã được xóa thành công.');
        } else {
            return redirect()->route('admin.danhmuc.index')->with('error', 'Danh mục không tồn tại.');
        }   
    }
}
