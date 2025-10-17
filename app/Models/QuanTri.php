<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class QuanTri extends Authenticatable
{
    use Notifiable;

    protected $table = 'quantri';
    protected $primaryKey = 'maQuanTri'; // Xác định rõ ràng
    protected $fillable = ['tenDangNhap', 'matKhau', 'email', 'soDienThoai', 'avatar'];
    public $timestamps = false;

    public function getAuthPassword(): string
    {
        return $this->matKhau;
    }

    // Đảm bảo guard 'admin' sử dụng model này
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    // Thêm phương thức để Laravel nhận diện primary key
    public function getKeyName()
    {
        return 'maQuanTri';
    }
}

    // public function getAuthPassword(): string
    // {
    //     return $this->matKhau;
    // }