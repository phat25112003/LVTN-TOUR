<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\DatCho;
use App\Models\Tour;
use App\Models\ChuyenTour;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'nullable|email|max:255',
        ]);

        $user->update([
            'hoTen'        => $request->hoTen,
            'soDienThoai'  => $request->soDienThoai,
            'diaChi'       => $request->diaChi,
            'email'        => $request->email,
            
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

    public function doiMatKhau(Request $request)
    {
        $request->validate([
            'password_old' => 'required',
            'password_new' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->password_old, $user->matKhau)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        // Cập nhật mật khẩu mới
        $user->matKhau = Hash::make($request->password_new);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function destroy($maDatCho)
    {
        $user = Auth::user();

        // Lấy đặt chỗ của chính user, kèm chuyến tour
        $datcho = $user->datCho()
            ->with(['chuyenTour', 'thanhToan', 'khachThamGia'])
            ->where('maDatCho', $maDatCho)
            ->firstOrFail();

        // Không cho xóa nếu đã xác nhận
        if ($datcho->xacNhan == 1) {
            return back()->with('error', 'Đơn đã xác nhận, không thể xóa.');
        }

        \DB::beginTransaction();

        try {
            // Tính tổng số người đã đặt
            $tongNguoi =
                ($datcho->soNguoiLon ?? 0) +
                ($datcho->soTreEm ?? 0) +
                ($datcho->soEmBe ?? 0);

            // Giảm số lượng đã đặt của chuyến tour
            if ($datcho->chuyenTour) {
                $datcho->chuyenTour->soLuongDaDat = max(
                    0,
                    $datcho->chuyenTour->soLuongDaDat - $tongNguoi
                );
                $datcho->chuyenTour->save();
            }
            // Xóa khuyến mãi đã sử dụng nếu có
            if ($datcho->khuyenMaiSuDung) {
            $datcho->khuyenMaiSuDung()->delete();
            }
            // Xóa thanh toán nếu có
            if ($datcho->thanhToan) {
                $datcho->thanhToan()->delete();
            }

            // Xóa khách tham gia
            $datcho->khachThamGia()->delete();

            // Xóa đặt chỗ
            $datcho->delete();

            DB::commit();

            return redirect()
                ->route('user.thongtinuser')
                ->with('success', 'Xóa tour thành công!');
        } catch (\Throwable $e) {
            \DB::rollBack();

            \Log::error('Lỗi xóa đặt chỗ', [
                'maDatCho' => $maDatCho,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

}
