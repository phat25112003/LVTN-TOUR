<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaTour extends Model
{
    protected $table = 'giatour';
    protected $primaryKey = 'maGiaTour';
    public $timestamps = false;
    protected $fillable = [
        'maChuyen',
        'emBe',
        'treEm',
        'nguoiLon',
    ];
    public function chuyenTour()
    {
        return $this->belongsTo(ChuyenTour::class, 'maChuyen', 'maChuyen');
    }
}
