<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichTrinh extends Model
{
    protected $table = 'lich_trinh';
    protected $primaryKey = 'maLichTrinh';
    protected $fillable = ['maTour', 'ngay', 'huongDi', 'sang', 'trua', 'chieu', 'toi'];
    public $timestamps = false;

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }
}