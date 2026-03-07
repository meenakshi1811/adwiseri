<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        //
        $this->data = $maildata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        if($data->attachment){
            return $this->subject("New Support Ticket Raised(".$data->ticket_id.")")
                ->view('web.supporttemplate',compact('data'))
                ->attach("web_assets/users/ticket_images/".$data->attachment);
        }
        else{
            return $this->subject("New Support Ticket Raised(".$data->ticket_id.")")->view('web.supporttemplate',compact('data'));
        }
    }
}
