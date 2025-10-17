<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'danhgia';
    protected $primaryKey = 'maDanhGia';
    protected $fillable = ['maTour', 'maNguoiDung', 'diemSo', 'binhLuan', 'thoiGian'];
    public $timestamps = false;

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'maNguoiDung', 'maNguoiDung');
    }
}