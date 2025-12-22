<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class KhuyenMai extends Model
{
    protected $table = 'khuyenmai';
    protected $primaryKey = 'maKM';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'code',
        'tenKM',
        'loaiKM',           // 'percent' | 'fixed'
        'giaTri',           // 15.00 hoặc 500000.00
        'giaTriToiDa',      // NULL hoặc số tiền cap (ví dụ 2000000)
        'apDung',           // tat_ca | danh_muc | tour_cu_the | chuyen_cu_the
        'danhMucIDs',       // JSON: [1,5]
        'tourIDs',          // JSON: [31,35]
        'chuyenIDs',        // JSON: [1,23]
        'soTienToiThieu',
        'ngayBatDau',
        'ngayKetThuc',
        'soLuotSuDungToiDa',
        'soLuotDaDung',
        'chiDungMoiNguoi1Lan', // 1 = true, 0 = false
        'trangThai',        // dang_chay | tam_dung | ket_thuc
    ];

    protected $casts = [
        'danhMucIDs' => 'array',
        'tourIDs'    => 'array',
        'chuyenIDs'  => 'array',
        'giaTri'     => 'float',
        'giaTriToiDa'=> 'float',
        'soTienToiThieu' => 'float',
        'ngayBatDau' => 'datetime',
        'ngayKetThuc'=> 'datetime',
        'chiDungMoiNguoi1Lan' => 'boolean',
    ];

    // ==================== SCOPE ====================
    // App\Models\KhuyenMai.php

    public function scopeActive($query)
    {
        return $query->where('trangThai', 'dang_chay')
                    ->where('ngayBatDau', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('ngayKetThuc')
                        ->orWhere('ngayKetThuc', '>=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('soLuotSuDungToiDa')
                        ->orWhereRaw('soLuotDaDung < soLuotSuDungToiDa');
                    });
    }

    // ==================== RELATIONSHIP ====================
    public function sudung()
    {
        return $this->hasMany(KhuyenMaiSuDung::class, 'maKM', 'maKM');
    }

    // ==================== HÀM CHÍNH – ÁP DỤNG MÃ ====================
    public function apDungChoDonHang($maDatCho, $maNguoiDung = null, $tongTien = 0, $tourIDs = [], $chuyenID = null)
    {
        // Dùng transaction ở Controller hoặc Service
        // Ở đây chỉ trả về kết quả tính toán + có hợp lệ hay không

        // 1. Kiểm tra hết hạn / lượt dùng
        if ($this->soLuotSuDungToiDa && $this->soLuotDaDung >= $this->soLuotSuDungToiDa) {
            return ['success' => false, 'message' => 'Mã khuyến mãi đã hết lượt sử dụng!'];
        }

        // 2. Đơn tối thiểu
        if ($tongTien < $this->soTienToiThieu) {
            return ['success' => false, 'message' => 'Đơn hàng chưa đủ điều kiện áp dụng mã này!'];
        }

        // 3. Mỗi người chỉ dùng 1 lần
        if ($this->chiDungMoiNguoi1Lan && $maNguoiDung) {
            $daDung = $this->sudung()->where('maNguoiDung', $maNguoiDung)->exists();
            if ($daDung) {
                return ['success' => false, 'message' => 'Bạn đã sử dụng mã này rồi!'];
            }
        }

        // 4. Kiểm tra áp dụng đúng tour/chuyến/danh mục
        if ($this->apDung !== 'tat_ca') {
            $hopLe = false;

            if ($this->apDung === 'tour_cu_the' && $this->tourIDs) {
                foreach ($tourIDs as $id) {
                    if (in_array($id, $this->tourIDs)) {
                        $hopLe = true; break;
                    }
                }
            }

            elseif ($this->apDung === 'chuyen_cu_the' && $this->chuyenIDs && $chuyenID) {
                $hopLe = in_array($chuyenID, $this->chuyenIDs);
            }

            elseif ($this->apDung === 'danh_muc') {
            // ← CHỈ 1 DÒNG DUY NHẤT – giống hệt chuyến cụ thể!
            $hopLe = DB::table('tour')
                    ->whereIn('maTour', $tourIDs)
                    ->whereIn('maDanhMuc', $this->danhMucIDs ?? [])
                    ->exists();
            }

            if (!$hopLe) {
                return ['success' => false, 'message' => 'Mã không áp dụng cho tour/chuyến này!'];
            }
        }

        // 5. Tính tiền giảm
        $giaGiam = $this->tinhGiaGiam($tongTien);

        return [
            'success' => true,
            'giaGiam' => $giaGiam,
            'tongMoi' => $tongTien - $giaGiam,
            'tenKM'   => $this->tenKM,
            'khuyenmai' => $this // trả luôn object nếu cần
        ];
    }

    // Tính tiền giảm thực tế
    public function tinhGiaGiam($tongTien)
    {
        // ===============================
        // KM giảm theo số tiền cố định
        // ===============================
        if ($this->loaiKM === 'fixed') {
            // giảm tối đa bằng tổng tiền
            $giam = min($this->giaTri, $tongTien);

            // nếu có giới hạn tối đa -> áp dụng
            if (!empty($this->giaTriToiDa)) {
                $giam = min($giam, $this->giaTriToiDa);
            }

            return $giam;
        }

        // ===============================
        // KM giảm theo %
        // ===============================
        if ($this->loaiKM === 'percent') {
            $giam = $tongTien * ($this->giaTri / 100);

            // áp dụng trần giảm tối đa (giaTriToiDa)
            if (!empty($this->giaTriToiDa)) {
                $giam = min($giam, $this->giaTriToiDa);
            }

            return round($giam);
        }

        // ===============================
        // loại freeservice hoặc không hợp lệ
        // ===============================
        return 0;
    }

}