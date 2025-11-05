<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class NguoiDung extends Authenticatable implements AuthenticatableContract
{
    use Notifiable;

    protected $table = 'nguoidung';
    protected $primaryKey = 'maNguoiDung';
    protected $fillable = ['tenDangNhap', 'hoTen', 'matKhau', 'email', 'soDienThoai', 'diaChi', 'tinhTrang', 'avatar', 'gioiTinh'];
    protected $hidden = ['matKhau', 'remember_token'];
    public $timestamps = false;

    public function getAuthPassword()
    {
        return $this->matKhau;
    }

    // Mối quan hệ với bảng đánh giá (danhGia)
    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'maNguoiDung');
    }

    // Mối quan hệ với bảng đặt chỗ (datCho)
    public function datCho()
    {
        return $this->hasMany(DatCho::class, 'maNguoiDung');
    }

    // Mối quan hệ với bảng lịch sử (lichSu) - giả định tên bảng là lich_su
    public function lichSu()
    {
        return $this->hasMany(LichSu::class, 'maNguoiDung');
    }
    public function thanhtoan()
    {
        return $this->hasMany(Thanhtoan::class, 'maNguoiDung');
    }
}