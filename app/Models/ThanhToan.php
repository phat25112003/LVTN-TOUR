<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $table = 'thanhtoan';
    protected $primaryKey = 'maThanhToan';
    protected $fillable = ['maDatCho', 'phuongThucThanhToan', 'soTien', 'tinhTrangThanhToan', 'maGiaoDich', 'ngayThanhToan'];
    public $timestamps = false;

    public function datCho()
    {
        return $this->belongsTo(DatCho::class, 'maDatCho', 'maDatCho');
    }
}