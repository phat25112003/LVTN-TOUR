<?php

// app/Models/GiaTour.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaTour extends Model
{
    protected $table = 'giatour';
    protected $primaryKey = 'maChuyen'; // DÙNG maChuyen làm PK
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['maChuyen', 'nguoiLon', 'treEm', 'emBe'];

    public function chuyen()
    {
        return $this->belongsTo(ChuyenTour::class, 'maChuyen', 'maChuyen');
    }
}