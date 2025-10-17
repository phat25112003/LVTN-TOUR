<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    protected $table = 'nguoidung';
    protected $primaryKey = 'maNguoiDung';
    protected $fillable = ['tenDangNhap', 'matKhau', 'email', 'soDienThoai', 'diaChi', 'tinhTrang', 'avatar', 'gioiTinh'];
    public $timestamps = false;

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