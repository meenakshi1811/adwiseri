<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanSubscriptionMail extends Mailable
{
    public $subscriberName;
    public $planDetails;
    public $validityDuration;
    public $title;

    // Constructor to pass the subscriber's name and the updated plan details
    public function __construct($subscriberName, $planDetails, $validityDuration,$title)
    {
        $this->subscriberName = $subscriberName;
        $this->planDetails = $planDetails;
        $this->validityDuration = $validityDuration;
        $this->title = $title;
    }

    // Build the message
    public function build()
    {
        return $this->subject($this->title)
                    ->view('web.new_subscriptiontemplate')
                    ->with([
                        'subscriberName' => $this->subscriberName,
                        'planDetails' => $this->planDetails,
                        'validityDuration' => $this->validityDuration,
                        'title'=>$this->title
                    ]);
    }
}
