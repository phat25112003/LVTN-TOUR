<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
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

    public function lichSu()
    {
        return $this->hasMany(LichSu::class, 'maNguoiDung');
    }
    public function thanhtoan()
    {
        return $this->hasMany(Thanhtoan::class, 'maNguoiDung');
    }
}