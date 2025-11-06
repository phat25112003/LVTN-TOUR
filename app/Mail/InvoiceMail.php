<?php
// app/Mail/InvoiceMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DatCho;
use App\Models\HoaDon;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datCho;
    public $hoaDon;
    public $giaNguoiLon;
    public $giaTreEm;
    public $giaEmBe;
    public $tongTienTinhToan;

    public function __construct(DatCho $datCho, HoaDon $hoaDon)
    {
        $this->datCho = $datCho;
        $this->hoaDon = $hoaDon;

        // Tính giá từ giatour
        $gia = $datCho->chuyen?->gia;
        $this->giaNguoiLon = $gia->nguoiLon ?? 0;
        $this->giaTreEm    = $gia->treEm ?? 0;
        $this->giaEmBe     = $gia->emBe ?? 0;

        $this->tongTienTinhToan = 
            ($datCho->soNguoiLon * $this->giaNguoiLon) +
            ($datCho->soTreEm * $this->giaTreEm) +
            ($datCho->soEmBe * $this->giaEmBe);
    }

    public function build()
    {
        return $this->subject("Hóa Đơn Điện Tử #{$this->datCho->maDatCho} - {$this->datCho->tour->tieuDe}")
                    ->view('emails.invoice');
    }
}