<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatCho extends Model
{
    protected $table = 'datcho';
    protected $primaryKey = 'maDatCho';
    protected $fillable = ['maNguoiDung', 'maTour', 'ngayDat', 'nguoiLon', 'treEm', 'tongGia'];
    public $timestamps = false;

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'maNguoiDung', 'maNguoiDung');
    }

    public function hoaDon()
    {
        return $this->hasOne(HoaDon::class, 'maDatCho', 'maDatCho');
    }

    public function thanhToan()
    {
        return $this->hasOne(ThanhToan::class, 'maDatCho', 'maDatCho');
    }
}