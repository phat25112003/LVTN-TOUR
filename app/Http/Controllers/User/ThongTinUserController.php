<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\DatCho;
use App\Models\Tour;
use App\Models\ChuyenTour;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ThongTinUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập trước.');
        }

        // Lấy tất cả đặt chỗ kèm tour và chuyến tour
        $datCho = $user->datCho()->with('tour', 'chuyenTour')->orderByDesc('ngayDat')->get();
        return view('user.thongtinuser', compact('user', 'datCho'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();  

        $request->validate([
            'hoTen' => 'required|string|max:255',
            'soDienThoai' => 'nullable|string|max:20',
            'diaChi' => 'nullable|string|max:255',
        ]);

        $user->update([
            'hoTen'        => $request->hoTen,
            'soDienThoai'  => $request->soDienThoai,
            'diaChi'       => $request->diaChi,
        ]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
    public function updateAvatar(Request $request)
    {
        $user = auth()->user(); 
        if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            $old = storage_path('app/public/avatar-users/' . $user->avatar);
            if (file_exists($old)) unlink($old);
        }
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        // 🔥 ĐƯỜNG DẪN TUỲ CHỈNH THEO THƯ MỤC BẠN ĐANG CÓ
        $avatarPath = storage_path('app/public/avatar-users/');

        // Xóa avatar cũ Nếu Tồn Tại
        if ($user->avatar && file_exists($avatarPath . $user->avatar)) {
            unlink($avatarPath . $user->avatar);
        }

        // Upload ảnh mới
        $file = $request->file('avatar');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Lưu vào đúng thư mục đang được sử dụng
        $file->move($avatarPath, $fileName);

        // Lưu DB
        $user->avatar = $fileName;
        $user->save();

        return back()->with('success', 'Cập nhật ảnh đại diện thành công!');
    }



    public function destroy($maDatCho)
    {
        $datcho = Auth::user()->datCho()->with('chuyenTour')->findOrFail($maDatCho);

        // Tính lại số người đã đặt
        $tongNguoi = 
            ($datcho->soNguoiLon ?? 0) +
            ($datcho->soTreEm ?? 0) +
            ($datcho->soEmBe ?? 0);

        // Giảm số lượng đã đặt trong chuyến tour
        if ($datcho->chuyenTour) {
            $datcho->chuyenTour->soLuongDaDat = max(
                0,
                $datcho->chuyenTour->soLuongDaDat - $tongNguoi
            );
            $datcho->chuyenTour->save();
        }

        // Xoá bản ghi thanh toán nếu có
        if ($datcho->thanhToan) {
            $datcho->thanhToan()->delete();
        }

        // Xoá đặt chỗ
        $datcho->khachThamGia()->delete();
        $datcho->delete();

        return redirect()->route('user.thongtinuser')
                        ->with('success', 'Xóa tour thành công!');
    }


}
