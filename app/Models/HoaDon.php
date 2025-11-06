<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoadon';
    protected $primaryKey = 'maHoaDon';
    protected $fillable = ['maDatCho', 'soTien', 'ngayTao', 'chiTiet', 'trangThai',];
    public $timestamps = false;

    public function datCho()
    {
        return $this->belongsTo(DatCho::class, 'maDatCho', 'maDatCho');
    }
}