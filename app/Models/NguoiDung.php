<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NguoiDung extends Authenticatable
{
    protected $table = 'nguoidung';
    protected $primaryKey = 'maNguoiDung';
    protected $fillable = ['tenDangNhap','hoTen','matKhau', 'email', 'soDienThoai', 'diaChi', 'tinhTrang', 'avatar', 'gioiTinh'];
    public $timestamps = false;

    public function getAuthPassword()
    {
        return $this->matKhau;
    }
    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'maNguoiDung', 'maNguoiDung');
    }

    public function datCho()
    {
        return $this->hasMany(DatCho::class, 'maNguoiDung', 'maNguoiDung');
    }

    public function lichSu()
    {
        return $this->hasMany(LichSu::class, 'maNguoiDung', 'maNguoiDung');
    }

    public function isActive()
    {
        return $this->tinhTrang == 1;
    }
}