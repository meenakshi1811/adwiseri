<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->data = $maildata;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        // $user = $this->user;
        if(isset($data->password)){
            return $this->subject(" adwiseri Password Recovery OTP")->view('web.emailtemplate',compact('data'));
        }
        elseif(isset($data->message)){
            return $this->subject("New Message from adwiseri.com (Contact Us)")->view('web.emailtemplate',compact('data'));
        }
        elseif(isset($data->how_did_hear)){
            return $this->subject("Demo Request from adwiseri.comc")->view('web.emailtemplate',compact('data'));
        }
        else{
            return $this->subject(" adwiseri Email Verification")->view('web.emailtemplate',compact('data'));
        }
        // return $this->view('web.emailtemplate',compact('data'));
    }
}
