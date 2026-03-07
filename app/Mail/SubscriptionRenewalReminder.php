<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewalReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $daysRemaining;

    public function __construct($subscriber, $daysRemaining)
    {
        $this->subscriber = $subscriber;
        $this->daysRemaining = $daysRemaining;
    }

    public function build()
    {
        return $this->subject("Renew Your Subscription - {$this->daysRemaining} Days Left")
            ->view('web.subscription_renewal_remindertemplate')
            ->with([
                'subscriber' => $this->subscriber,
                'daysRemaining' => $this->daysRemaining,
                'renewalLink' => route('price_plans', ['id' => $this->subscriber->id]),
            ]);
    }
}
