<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnh extends Model
{
    protected $table = 'hinhanh';
    protected $primaryKey = 'maHinhAnh';
    protected $fillable = ['moTa', 'duongDanHinh', 'tourid'];
    public $timestamps = false;

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tourid', 'maTour');
    }
}