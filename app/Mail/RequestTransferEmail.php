<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestTransferEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data,$date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$date)
    {
        $this->data = $data;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Request Transfer Report'))
            ->markdown('emails.request-transfer-email');
    }
}