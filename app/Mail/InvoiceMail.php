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
public $tongTienTinhToan;

public function __construct($datCho, $hoaDon, $tongTienTinhToan)
{
    $this->datCho = $datCho;
    $this->hoaDon = $hoaDon;
    $this->tongTienTinhToan = $tongTienTinhToan;
}

public function build()
{
    return $this->view('emails.invoice')
                ->subject('Hóa đơn điện tử - Đặt tour thành công #'.str_pad($this->datCho->maDatCho, 6, '0', STR_PAD_LEFT));
}
}