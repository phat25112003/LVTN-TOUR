<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhuyenMai;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KhuyenMaiUserController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code'      => 'required|string',
            'maTour'    => 'required|integer',
            'maChuyen'  => 'nullable|integer',
            'tongTien'  => 'required|numeric',
            'maNguoiDung' => 'nullable|integer',
        ]);

        $code        = $request->code;
        $tongTien    = $request->tongTien;
        $maTour      = $request->maTour;
        $maChuyen    = $request->maChuyen;
        $maNguoiDung = $request->maNguoiDung;

        // 1. Tìm mã khuyến mãi còn hiệu lực
        $km = KhuyenMai::active()->where('code', $code)->first();

        if (!$km) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn!'
            ]);
        }

        // 2. Gọi hàm xử lý trong Model
        $rs = $km->apDungChoDonHang(
            null,      // chưa tạo maDatCho
            $maNguoiDung,
            $tongTien,
            [$maTour],
            $maChuyen
        );

        if (!$rs['success']) {
            return response()->json($rs);
        }

        return response()->json([
            'success' => true,
            'giaGiam' => $rs['giaGiam'],
            'tongMoi' => $rs['tongMoi'],
            'tenKM'   => $rs['tenKM'],
            'maKM'    => $km->maKM
        ]);
    }
}
