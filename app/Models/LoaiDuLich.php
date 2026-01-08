<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiDuLich extends Model
{
    protected $table = 'loaidulich';
    protected $primaryKey = 'maLoai';
    public $timestamps = false;

    protected $fillable = [
        'tenLoai',
        'moTa'
    ];

    public function tour()
    {
        return $this->hasMany(Tour::class, 'maLoai', 'maLoai');
    }
    
}
