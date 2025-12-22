<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class DoanhThuExport implements FromCollection, WithHeadings, WithMapping
{
    protected $chiTiet;
    protected $tuNgay;
    protected $denNgay;
    protected $tongDonDat;
    protected $tongDonThanhToan;
    protected $tongDoanhThu;

    public function __construct($chiTiet, $tuNgay, $denNgay, $tongDonDat, $tongDonThanhToan, $tongDoanhThu)
    {
        $this->chiTiet           = $chiTiet;
        $this->tuNgay            = $tuNgay;
        $this->denNgay           = $denNgay;
        $this->tongDonDat        = $tongDonDat;
        $this->tongDonThanhToan  = $tongDonThanhToan;
        $this->tongDoanhThu      = $tongDoanhThu;
    }

    public function collection(): Collection
    {
        $data = collect([
            ['BÁO CÁO DOANH THU CHI TIẾT - TRAVELTIME'],
            ['Từ ngày: ' . $this->tuNgay, 'Đến ngày: ' . $this->denNgay],
            [],
            ['Tổng số đơn đặt:', $this->tongDonDat . ' đơn'],
            ['Tổng số đơn đã thanh toán:', $this->tongDonThanhToan . ' đơn'],
            [
                'Tỷ lệ chuyển đổi:',
                $this->tongDonDat > 0
                    ? round(($this->tongDonThanhToan / $this->tongDonDat) * 100, 1) . '%'
                    : '0%'
            ],
            ['Tổng doanh thu thực tế:', number_format((float)$this->tongDoanhThu) . ' ₫'],
            [],
        ]);

        // Tiêu đề bảng
        $data->push([
            'STT',
            'Ngày đặt',
            'Họ tên khách',
            'Số điện thoại',
            'Email',
            'Tên tour',
            'Ngày khởi hành',
            'Số khách',
            'Tổng tiền đặt',
            'Trạng thái thanh toán',
            'Doanh thu thực tế (₫)'
        ]);

        // Dữ liệu từng đơn
        foreach ($this->chiTiet as $index => $item) {
            $trangThai = strip_tags($item['thanh_toan']);

            $doanhThuHienThi = $item['doanh_thu'] > 0 
                ? number_format($item['doanh_thu']) 
                : ''; // Để trống nếu chưa thanh toán

            $data->push([
                $index + 1,
                $item['ngay_dat'],
                $item['ho_ten'],
                $item['dien_thoai'],
                $item['email'],
                $item['tour'],
                $item['ngay_di'],
                $item['so_khach'] . ' khách',
                $item['tong_tien_dat'],
                $trangThai,
                $doanhThuHienThi, // Chỉ hiện tiền nếu đã thanh toán
            ]);
        }

        // Dòng tổng cộng
        $data->push([]);
        $data->push([
            'TỔNG CỘNG',
            '',
            '',
            '',
            '',
            '',
            '',
            $this->tongDonDat . ' đơn',
            '',
            $this->tongDonThanhToan . ' đơn đã thanh toán',
            number_format($this->tongDoanhThu) . ' ₫'
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function map($row): array
    {
        return $row;
    }
}