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
        // Giữ nguyên logic lấy dữ liệu
        $chuyen = ChuyenTour::with(['datCho.khachThamGia', 'tour', 'huongdanvien'])->findOrFail($this->maChuyen);

        $khachs = $chuyen->datCho
                         ->pluck('khachThamGia')
                         ->flatten()
                         ->sortBy('maKhach');

        $data = collect();
        $stt = 1;
        foreach ($khachs as $khach) {
            $data->push([
                'STT' => $stt++,
                'Họ và tên' => $khach->hoTenKhach,
                'Tuổi' => $khach->tuoi,
                'Giới tính' => $khach->gioiTinh == 'Nam' ? 'Nam' : 'Nữ',
                'Lựa chọn phòng' => $khach->luaChonPhong == 'PhongDon' ? 'Phòng đơn' : 'Ghép phòng',
                'Mã booking' => '#' . str_pad($khach->maDatCho, 6, '0', STR_PAD_LEFT),
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        // Headings này sẽ được dùng cho bảng khách
        return [
            'STT',
            'Họ và tên',
            'Tuổi',
            'Giới tính',
            'Lựa chọn phòng',
            'Mã booking',
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
                $headerRowNumber = 1; // Header (headings) luôn là dòng 1 sau khi FromCollection chạy
                $dataStartRow = $headerRowNumber + 1;
                $dataEndRow = $dataStartRow + $totalDataRows - 1;

                // --- 1. Tạo Tiêu đề Lớn cho Danh sách Khách (Dòng 1, 2) ---
                $sheet->insertNewRowBefore(1, 2); // Chèn 2 dòng trống ở đầu (Dòng 1, 2)
                
                // Cắt và Dán dữ liệu (đã bao gồm Header) xuống dưới
                // Dữ liệu ban đầu (Header và Data) nằm ở Dòng 3 đến Dòng dataEndRow + 2
                
                // Bây giờ, Header mới nằm ở Dòng 3
                $sheet->getStyle("A3:F3")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '008080']], // Màu Teal/Xanh đậm
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                
                // Tiêu đề lớn (Dòng 1)
                $sheet->setCellValue('A1', 'DANH SÁCH KHÁCH THAM GIA TOUR');
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getFont()->setSize(20)->setBold(true)->setColor(new Color(Color::COLOR_DARKRED));
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tiêu đề phụ (Dòng 2): Mã chuyến
                $sheet->setCellValue('A2', 'Chuyến: #00' . $chuyen->maChuyen . ' | Tour: ' . $chuyen->tour->tieuDe);
                $sheet->mergeCells('A2:F2');
                $sheet->getStyle('A2')->getFont()->setSize(14)->setBold(true)->setColor(new Color('FF555555'));
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // --- 2. Định dạng Bảng Khách (Nằm ở Dòng 3 trở đi) ---
                
                $dataStartRow = 4;
                $lastRow = $sheet->getHighestRow();

                // Định dạng căn giữa cho dữ liệu
                $sheet->getStyle("A{$dataStartRow}:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$dataStartRow}:C{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$dataStartRow}:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("F{$dataStartRow}:F{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // --- 3. Thêm Thông tin Chuyến ở Dưới ---
                
                $row = $lastRow + 2; // Bắt đầu thông tin chuyến sau 1 dòng trống

                // Định dạng tiêu đề Thông tin chuyến
                $sheet->setCellValue("A{$row}", 'THÔNG TIN CHUYẾN TÓM TẮT:');
                $sheet->mergeCells("A{$row}:F{$row}");
                $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14)->setColor(new Color('FF000080')); // Xanh Navy
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $row++;

                // Chi tiết thông tin chuyến
                $sheet->setCellValue("A{$row}", 'Tour:'); $sheet->setCellValue("B{$row}", $chuyen->tour->tieuDe); $row++;
                $sheet->setCellValue("A{$row}", 'Thời gian:'); $sheet->setCellValue("B{$row}", \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d/m/Y') . ' → ' . \Carbon\Carbon::parse($chuyen->ngayKetThuc)->format('d/m/Y')); $row++;
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
                $row++;

                // Định dạng cột A và B của thông tin chuyến
                $infoStartRow = $lastRow + 3;
                $sheet->getStyle("A{$infoStartRow}:A{$row}")->getFont()->setBold(true);

                // --- 4. Auto size (Giữ nguyên) ---
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}