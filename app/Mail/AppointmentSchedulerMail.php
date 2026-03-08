<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentSchedulerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $appointment,
        public $client,
        public $sender
    ) {
    }

    public function build()
    {
        return $this->subject('Appointment Invitation - Respond Required')
            ->view('web.appointment_scheduler_mail')
            ->with([
                'appointment' => $this->appointment,
                'client' => $this->client,
                'sender' => $this->sender,
            ]);
    }
}
