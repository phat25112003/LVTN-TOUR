<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuyenTour extends Model
{
    protected $table = 'chuyentour';
    protected $primaryKey = 'maChuyen';
    public $timestamps = false;

    protected $fillable = [
        'maTour', 'ngayBatDau', 'ngayKetThuc', 'diemKhoiHanh',
        'maHDV', 'phuongTien', 'soLuongToiDa', 'soLuongDaDat',
        'tinhTrangChuyen', 'ghiChu', 'so_khach_toi_thieu',
    ];

    protected $casts = [
        'tinhTrangChuyen' => 'string',
        'ngayBatDau' => 'date',
        'ngayKetThuc' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }

    public function giaTour()
    {
        return $this->hasOne(GiaTour::class, 'maChuyen', 'maChuyen');
    }

    public function huongDanVien()
    {
        return $this->belongsTo(HuongDanVien::class, 'maHDV', 'maHDV');
    }

    public function datCho()
    {
        return $this->hasMany(DatCho::class, 'maChuyen', 'maChuyen');
    }
    // public function khachThamGia()
    // {
    //     return $this->hasMany(KhachThamGia::class, 'maDatCho', 'maDatCho')
    //                 ->orWhereNull('maDatCho'); // trick để load cả khách ghép
    // }
    // Trong model ChuyenTour.php
    public function khachGhep()
    {
        return $this->hasMany(KhachThamGia::class, 'maChuyen')
                    ->whereNull('maDatCho');
    }
}
