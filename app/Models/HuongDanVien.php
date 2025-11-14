<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HuongDanVien extends Model
{
    protected $table = 'huongdanvien';
    protected $primaryKey = 'maHDV';

    // THÊM 'avatar' VÀO fillable
    protected $fillable = [
        'hoTen', 'soDienThoai', 'avatar', 'email', 'diaChi', 'soCCCD', 'ngaySinh', 'gioiTinh', 'ghiChu', 'trangThai'
    ];

    public function chuyenTours()
    {
        return $this->hasMany(ChuyenTour::class, 'maHDV', 'maHDV');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/avatar-hdv/' . $this->avatar)
            : asset('storage/avatar-hdv/default.jpg');
    }
}