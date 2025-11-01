<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $table = 'tour';
    protected $primaryKey = 'maTour';
    public $timestamps = false;
    protected $fillable = [
        'tieuDe',
        'thoiGian',
        'moTa',
        'ngayBatDau',
        'ngayKetThuc',
        'hinhAnh',
        'soLuong',
        'giaNguoiLon',
        'giaTreEm',
        'diemDen',
        'tinhTrang',
        'maDanhMuc',
    ];

    public function hinhAnh()
    {
        return $this->hasMany(HinhAnh::class, 'maTour', 'maTour');
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


    public function lichtrinh()
    {
        return $this->hasMany(LichTrinh::class, 'maTour', 'maTour');
    }

    public function danhmuc()
    {
        return $this->belongsTo(DanhMuc::class, 'maDanhMuc', 'maDanhMuc');
    }

    
    protected $casts = [
        'ngayBatDau' => 'date:Y-m-d',
        'ngayKetThuc' => 'date:Y-m-d',
    ];


}
