<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachThamGia extends Model
{
    protected $table = 'khachthamgia';
    protected $primaryKey = 'maKhach';

    protected $fillable = [
        'hoTenKhach',
        'tuoi',
        'gioiTinh',
        'luaChonPhong',
        'maDatCho',
        'maVe',
    ];
    public $timestamps = false; // ✅ QUAN TRỌNG

    public function datCho()
    {
        return $this->belongsTo(DatCho::class, 'maDatCho', 'maDatCho');
    }
}