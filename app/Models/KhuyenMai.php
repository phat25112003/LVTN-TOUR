<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    protected $table = 'khuyenmai'; // Khai báo tên bảng thực tế
    protected $fillable = ['maKhuyenMai', 'tenKhuyenMai', 'mucGiam', 'loaiGiam', 'ngayBatDau', 'ngayKetThuc', 'tinhTrang', 'maTour','apDungTatCaTour'];
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }
}