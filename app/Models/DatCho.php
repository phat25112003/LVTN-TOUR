<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatCho extends Model
{
    use HasFactory;

    protected $table = 'datcho';
    protected $primaryKey = 'maDatCho';
    protected $fillable = [
        'maNguoiDung',
        'maTour',
        'ngayDat',
        'ngayKhoiHanh',
        'ngayKetThuc',
        'nguoiLon',
        'treEm',
        'tongGia',
        'diaChi',
        'soDienThoai',
        'email',
        'phuongThucThanhToan',
        'xacNhan',
    ];

    public $timestamps = false; // Tắt timestamps để tránh lỗi created_at/updated_at

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'maNguoiDung', 'maNguoiDung');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour', 'maTour');
    }

    public function hoadon()
    {
        // Sử dụng hasOne vì khóa ngoại maDatCho nằm trong bảng hoadon
        return $this->hasOne(HoaDon::class, 'maDatCho', 'maDatCho');
    }

    public function thanhtoan()
    {
        // Giả định khóa ngoại là maDatCho
        return $this->hasOne(ThanhToan::class, 'maDatCho', 'maDatCho');
    }
}