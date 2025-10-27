<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    protected $table = 'danhmuc';
    protected $primaryKey = 'maDanhMuc';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['tenDanhMuc'];

    public $timestamps = false; // Tắt hoàn toàn tính năng timestamps
}