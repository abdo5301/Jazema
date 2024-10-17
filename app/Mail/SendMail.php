<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->data['subject'];
        $this->subject($subject);
//        $this->text($this->data['message']);
        $this->from($this->data['email'],$this->data['name']);

//        if($this->data->file){
//            $this->attach($this->data->file);
//        }
        return $this->markdown('emails.send.mail',['data'=>$this->data]);
    }
}
