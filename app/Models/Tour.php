<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $table = 'tour';
    protected $primaryKey = 'maTour';
    protected $fillable = ['tieuDe', 'moTa', 'hinhAnh', 'soLuong', 'giaNguoiLon', 'giaTreEm', 'thoiLuong', 'diemDen', 'tinhTrang'];
    public $timestamps = false;

    public function hinhAnh()
    {
        return $this->hasMany(HinhAnh::class, 'tourid', 'maTour');
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'maTour', 'maTour');
    }

    public function datCho()
    {
        return $this->hasMany(DatCho::class, 'maTour', 'maTour');
    }

    public function khuyenMai()
    {
        return $this->hasMany(KhuyenMai::class, 'maTour', 'maTour');
    }

    public function lichSu()
    {
        return $this->hasMany(LichSu::class, 'maTour', 'maTour');
    }
}