<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    protected $table = 'binhluan';
    protected $primaryKey = 'maBinhLuan';
    public $timestamps = true;

    protected $fillable = [
        'maNguoiDung',
        'maTour',
        'noiDung',
        'danhGia',
        'created_at',
    ];

    // Mối quan hệ với bảng người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'maNguoiDung');
    }

    // Mối quan hệ với bảng tour
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour');
    }
    
}
