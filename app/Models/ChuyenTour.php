<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuyenTour extends Model
{
    protected $table = 'chuyentour';
    protected $primaryKey = 'maChuyen';
    public $timestamps = false;
    protected $fillable = [
        'maTour',
        'ngayBatDau',
        'ngayKetThuc',
        'diemKhoiHanh',
        'huongDanVien',
        'phuongTien',
        'soLuongToiDa',
        'soLuongDaDat',
        'tinhTrangChuyen',
        'ghiChu',
        'ngayTao',
        'ngayCapNhat',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }
    public function datCho()
    {
        return $this->hasMany(DatCho::class, 'maChuyen', 'maChuyen');
    }
    public function giatour()
    {
        return $this->hasOne(GiaTour::class, 'maChuyen', 'maChuyen');
    }
}
