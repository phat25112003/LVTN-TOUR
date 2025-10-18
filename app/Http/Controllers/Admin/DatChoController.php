<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatCho;
use Illuminate\Http\Request;

class DatChoController extends Controller
{
    public function index()
    {
        $datChos = DatCho::with(['nguoiDung', 'tour'])->orderBy('ngayDat', 'desc')->get();
        return view('admin.datcho.index', compact('datChos'));
    }

    public function xacNhan($maDatCho)
    {
        $datCho = DatCho::findOrFail($maDatCho);
        if ($datCho->xacNhan == 0) {
            $datCho->update(['xacNhan' => 1]);
            return redirect()->route('admin.datcho.index')->with('success', 'Xác nhận tour thành công.');
        }
    }
}
?>