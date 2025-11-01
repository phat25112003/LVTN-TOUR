<?php
// app/Models/ThanhToan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    use HasFactory;

    protected $table = 'thanhtoan';
    protected $primaryKey = 'maThanhToan'; // Giả định
    protected $fillable = [
        'maDatCho',
        'phuongThucThanhToan',
        'soTien',
        'tinhTrangThanhToan', // Cần cập nhật cột này
        'maGiaoDich',
        'ngayThanhToan',
    ];

    public $timestamps = false; 

    public function datCho()
    {
        return $this->belongsTo(DatCho::class, 'maDatCho', 'maDatCho');
    }
}