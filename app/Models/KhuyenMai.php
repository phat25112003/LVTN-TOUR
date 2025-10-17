<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    protected $table = 'khuyenmai';
    protected $primaryKey = 'maKhuyenMai';
    protected $fillable = ['maTour', 'tenKhuyenMai', 'phanTramGiam', 'ngayBatDau', 'ngayKetThuc', 'moTa'];
    public $timestamps = false;

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }
}