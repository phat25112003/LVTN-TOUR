<?php
<<<<<<< HEAD
=======
// app/Mail/InvoiceMail.php
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad

namespace App\Mail;

use Illuminate\Bus\Queueable;
<<<<<<< HEAD
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
=======
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DatCho;
use App\Models\HoaDon;
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

<<<<<<< HEAD
    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
=======
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
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
