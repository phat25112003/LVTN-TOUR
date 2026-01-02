<?php

namespace App\Exports;

use App\Models\ChuyenTour;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class KhachThamGiaChuyenExport implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    protected $maChuyen;

    public function __construct($maChuyen)
    {
        $this->maChuyen = $maChuyen;
    }

    public function collection()
    {
        // Load thêm quan hệ thanhtoan để lấy trạng thái thanh toán
        $chuyen = ChuyenTour::with([
            'datCho.khachThamGia',
            'datCho.thanhtoan', // <<< QUAN TRỌNG: để kiểm tra trạng thái thanh toán
            'tour',
            'huongdanvien'
        ])->findOrFail($this->maChuyen);

        $khachs = $chuyen->datCho
                         ->pluck('khachThamGia')
                         ->flatten()
                         ->sortBy('maKhach');

        $data = collect();
        $stt = 1;
        foreach ($khachs as $khach) {
            // Tìm đơn đặt chỗ của khách này để lấy trạng thái thanh toán
            $datChoCuaKhach = $chuyen->datCho->firstWhere('maDatCho', $khach->maDatCho);
            $trangThaiTT = $datChoCuaKhach?->thanhtoan?->tinhTrangThanhToan ?? 'Chưa thanh toán';

            $data->push([
                'STT' => $stt++,
                'Họ và tên' => $khach->hoTenKhach,
                'Tuổi' => $khach->tuoi,
                'Giới tính' => $khach->gioiTinh == 'Nam' ? 'Nam' : 'Nữ',
                'Lựa chọn phòng' => $khach->luaChonPhong == 'PhongDon' ? 'Phòng đơn' : 'Ghép phòng',
                'Mã booking' => '#' . str_pad($khach->maDatCho, 6, '0', STR_PAD_LEFT),
                'Trạng thái thanh toán' => $trangThaiTT, // <<< CỘT MỚI
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'STT',
            'Họ và tên',
            'Tuổi',
            'Giới tính',
            'Lựa chọn phòng',
            'Mã booking',
            'Trạng thái thanh toán', // <<< THÊM TIÊU ĐỀ CỘT MỚI
        ];
    }

    public function title(): string
    {
        return 'Danh sách khách';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $chuyen = ChuyenTour::with(['tour', 'huongdanvien'])->findOrFail($this->maChuyen);
                $sheet = $event->sheet->getDelegate();

                $totalDataRows = $this->collection()->count();
                $headerRowNumber = 1;
                $dataStartRow = $headerRowNumber + 1;
                $dataEndRow = $dataStartRow + $totalDataRows - 1;

                // Chèn 2 dòng tiêu đề ở trên
                $sheet->insertNewRowBefore(1, 2);

                // Header bảng khách giờ nằm ở dòng 3
                $sheet->getStyle("A3:G3")->applyFromArray([ // <<< G3 vì có 7 cột
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '008080']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Tiêu đề lớn
                $sheet->setCellValue('A1', 'DANH SÁCH KHÁCH THAM GIA TOUR');
                $sheet->mergeCells('A1:G1'); // <<< G1 vì 7 cột
                $sheet->getStyle('A1')->getFont()->setSize(20)->setBold(true)->setColor(new Color(Color::COLOR_DARKRED));
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tiêu đề phụ
                $sheet->setCellValue('A2', 'Chuyến: #00' . $chuyen->maChuyen . ' | Tour: ' . $chuyen->tour->tieuDe);
                $sheet->mergeCells('A2:G2'); // <<< G2
                $sheet->getStyle('A2')->getFont()->setSize(14)->setBold(true)->setColor(new Color('FF555555'));
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Định dạng dữ liệu
                $dataStartRow = 4;
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A{$dataStartRow}:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$dataStartRow}:C{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$dataStartRow}:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G{$dataStartRow}:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Cột trạng thái

                // Tô màu nền cho cột "Trạng thái thanh toán"
                foreach (range($dataStartRow, $lastRow) as $row) {
                    $cellValue = $sheet->getCell("G{$row}")->getValue();
                    if ($cellValue === 'Đã thanh toán') {
                        $sheet->getStyle("G{$row}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('D4EDDA'); // Xanh nhạt
                        $sheet->getStyle("G{$row}")->getFont()->setBold(true)->setColor(new Color('155724'));
                    } else {
                        $sheet->getStyle("G{$row}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFF3CD'); // Vàng nhạt
                        $sheet->getStyle("G{$row}")->getFont()->setColor(new Color('856404'));
                    }
                }

                // Thông tin chuyến ở dưới
                $row = $lastRow + 2;

                $sheet->setCellValue("A{$row}", 'THÔNG TIN CHUYẾN TÓM TẮT:');
                $sheet->mergeCells("A{$row}:G{$row}"); // <<< G
                $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14)->setColor(new Color('FF000080'));
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $row++;

                $sheet->setCellValue("A{$row}", 'Tour:'); $sheet->setCellValue("B{$row}", $chuyen->tour->tieuDe); $row++;
                $sheet->setCellValue("A{$row}", 'Thời gian:'); 
                $sheet->setCellValue("B{$row}", \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d/m/Y') . ' → ' . \Carbon\Carbon::parse($chuyen->ngayKetThuc)->format('d/m/Y')); 
                $row++;
                $sheet->setCellValue("A{$row}", 'Khởi hành:'); $sheet->setCellValue("B{$row}", $chuyen->diemKhoiHanh ?? 'TP. Hồ Chí Minh'); $row++;
                $sheet->setCellValue("A{$row}", 'Phương tiện:'); $sheet->setCellValue("B{$row}", $chuyen->phuongTien ?? 'Xe du lịch'); $row++;

                if ($chuyen->huongdanvien) {
                    $sheet->setCellValue("A{$row}", 'HDV:');
                    $sheet->setCellValue("B{$row}", $chuyen->huongdanvien->hoTen . ' (' . $chuyen->huongdanvien->soDienThoai . ')');
                    $row++;
                }

                $totalKhach = $chuyen->datCho->sum(fn($dc) => $dc->soNguoiLon + $dc->soTreEm + $dc->soEmBe);
                $sheet->setCellValue("A{$row}", 'Tổng số khách:');
                $sheet->setCellValue("B{$row}", $totalKhach . ' người');

                $infoStartRow = $lastRow + 3;
                $sheet->getStyle("A{$infoStartRow}:A{$row}")->getFont()->setBold(true);

                // Auto size tất cả cột (A đến G)
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}