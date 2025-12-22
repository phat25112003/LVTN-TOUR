<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatCho extends Model
{
    use HasFactory;

    protected $table = 'datcho';
    protected $primaryKey = 'maDatCho';

    // app/Models/DatCho.php
    protected $fillable = [
        'hoTen',
        'maNguoiDung',
        'maTour',
        'maChuyen',
        'ngayDat',
        'ngayhethan',
        'tongGia',
        'diaChi',           
        'soDienThoai',      
        'email',
        'phuongThucThanhToan',
        'xacNhan',
        'soNguoiLon',       
        'soTreEm',          
        'soEmBe',           
    ];

    public $timestamps = false; 

    // === QUAN HỆ ===
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'maNguoiDung');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'maTour');
    }

    public function hoadon()
    {
        return $this->hasOne(HoaDon::class, 'maDatCho');
    }

    public function thanhtoan()
    {
        return $this->hasOne(ThanhToan::class, 'maDatCho');
    }

    public function chuyentour()
    {
        return $this->belongsTo(ChuyenTour::class, 'maChuyen', 'maChuyen');
    }
    // === ACCESSOR: Tính tổng người ===
    public function getTongNguoiAttribute()
    {
        return $this->soNguoiLon + $this->soTreEm + $this->soEmBe;
    }

    // === ACCESSOR: Định dạng tiền ===
    public function getTongGiaFormattedAttribute()
    {
        return number_format($this->tongGia) . '₫';
    }
    
    public function khuyenMaiDaDung() 
    {
        return $this->hasMany(KhuyenMaiSuDung::class, 'maDatCho', 'maDatCho');
    }

    public function khuyenmai()
    {
        return $this->hasMany(KhuyenMaiSuDung::class, 'maDatCho', 'maDatCho');
    }
    public function khachThamGia()
    {
        return $this->hasMany(KhachThamGia::class, 'maDatCho', 'maDatCho');
    }
}