<?php
namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $pdf;

    public function __construct($booking, $pdf)
    {
        $this->booking = $booking;
        $this->pdf = $pdf;
    }


    public function build()
    {
        return $this->subject('Booking Confirmation - Rental Pro')
                    ->view('emails.booking_confirmation')
                    ->attachData($this->pdf, 'invoice_' . $this->booking->booking_no . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
    
}
