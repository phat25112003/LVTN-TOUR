<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuyenMaiSuDung extends Model
{
    protected $table = 'khuyenmai_sudung';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'maKM', 'maDatCho', 'maNguoiDung', 'giaGiam'
    ];

    public function khuyenmai()
    {
        return $this->belongsTo(KhuyenMai::class, 'maKM', 'maKM');
    }

    public function datcho()
    {
        return $this->belongsTo(DatCho::class, 'maDatCho', 'maDatCho');
    }
}