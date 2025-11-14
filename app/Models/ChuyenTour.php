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
        'tinhTrangChuyen', 'ghiChu'
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

    public function datChos()
    {
        return $this->hasMany(DatCho::class, 'maChuyen', 'maChuyen');
    }
}