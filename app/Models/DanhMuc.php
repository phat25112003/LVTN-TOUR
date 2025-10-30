<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    protected $table = 'danhmuc';
    protected $primaryKey = 'maDanhMuc';
    public $timestamps = false;

    protected $fillable = [
        'tenDanhMuc',
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class, 'maDanhMuc', 'maDanhMuc');
    }
}
