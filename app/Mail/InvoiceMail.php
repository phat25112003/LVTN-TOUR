<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datCho;
    public $hoaDon;
    public $tongGiaGoc;
    public $tongGiamGia;
    public $thanhTien;

    public function __construct($datCho, $hoaDon, $tongGiaGoc, $tongGiamGia, $thanhTien)
    {
        $this->datCho       = $datCho;
        $this->hoaDon       = $hoaDon;
        $this->tongGiaGoc   = $tongGiaGoc;
        $this->tongGiamGia  = $tongGiamGia;
        $this->thanhTien    = $thanhTien;
    }

    public function build()
    {
        return $this->subject('Hóa Đơn Điện Tử TravelTime - Mã đặt chỗ #' . str_pad($this->datCho->maDatCho, 6, '0', STR_PAD_LEFT))
                    ->view('emails.invoice');
    }
}