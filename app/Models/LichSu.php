<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSu extends Model
{
    protected $table = 'lichsu';
    protected $primaryKey = 'maLichSu';
    protected $fillable = ['maNguoiDung', 'maTour', 'trangThai', 'thoiGian'];
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