<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachThamGia extends Model
{
    protected $table = 'khachthamgia';
    protected $primaryKey = 'maKhach';
    public $timestamps = false;
    protected $fillable = [
        'hoTenKhach',
        'gioiTinh',
        'tuoi',
        'maDatCho',
        'luaChonPhong'
    ];
    public function datcho()
    {
        return $this->belongsTo(DatCho::class, 'maDatCho', 'maDatCho');
    }
}
