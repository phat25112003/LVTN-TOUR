<?php

namespace App\Exports;

use App\Models\KhachThamGia;
use App\Models\ChuyenTour;
use App\Models\Tour;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class KhachThamGiaChuyenExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    protected $maChuyen;

    public function __construct($maChuyen)
    {
        $this->maChuyen = $maChuyen;
    }

    public function collection()
    {
        return KhachThamGia::where('maChuyen', $this->maChuyen)->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Họ tên khách',
            'Tuổi',
            'Giới tính',
            'Loại phòng',
            'Mã Booking',
            'Thanh toán',
            'Ghi chú (từ đơn đặt chỗ)',
            'Tên tour',
            'Chuyến tour (ngày khởi hành - kết thúc)',
        ];
    }

    public function map($khach): array
    {
        static $stt = 1;

        $chuyen = ChuyenTour::find($khach->maChuyen);
        $tour = Tour::find($chuyen->maTour);

        $maBooking = $khach->maDatCho ? '#000' . $khach->maDatCho : 'Khách ghép';

        $thanhToan = $khach->maDatCho && $khach->datCho->xacNhan == 1 ? 'Đã thanh toán' : 'Chưa thanh toán';

        $ghiChu = $khach->maDatCho ? $khach->datCho->ghiChu : '';

        $tourInfo = $tour->tieuDe;

        $chuyenInfo = $chuyen->ngayBatDau->format('d/m/Y') . ' → ' . $chuyen->ngayKetThuc->format('d/m/Y');

        return [
            $stt++,
            $khach->hoTenKhach,
            $khach->tuoi,
            $khach->gioiTinh,
            $khach->luaChonPhong == 'PhongDon' ? 'Phòng đơn' : 'Ghép phòng',
            $maBooking,
            $thanhToan,
            $ghiChu,
            $tourInfo,
            $chuyenInfo,
        ];
    }

    public function title(): string
    {
        $chuyen = ChuyenTour::find($this->maChuyen);
        return 'Danh sách khách - Chuyến ' . $this->maChuyen;
    }
}