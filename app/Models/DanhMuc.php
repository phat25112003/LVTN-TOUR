<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DanhMuc extends Model
{
    protected $table = 'danhmuc';
    protected $primaryKey = 'maDanhMuc';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['tenDanhMuc'];

    public $timestamps = false; // Tắt hoàn toàn tính năng timestamps

    public function diaDiem():HasMany
    {
        return $this->hasMany(DiaDiem::class, 'maDanhMuc', 'maDanhMuc');
    }
}

