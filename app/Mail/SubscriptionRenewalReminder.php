<?php

namespace App\Mail;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
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
        $template = app(EmailTemplateService::class)->getTemplateForUser($this->subscriber, 'subscriber', 'subscription_expiry_reminder');

        if (!$template) {
            return $this->subject("Renew Your Subscription - {$this->daysRemaining} Days Left")
                ->view('web.subscription_renewal_remindertemplate')
                ->with([
                    'subscriber' => $this->subscriber,
                    'daysRemaining' => $this->daysRemaining,
                    'renewalLink' => route('price_plans', ['id' => $this->subscriber->id]),
                ]);
        }

        $data = [
            'name' => $this->subscriber->name,
            'daysRemaining' => $this->daysRemaining,
            'renewalLink' => route('price_plans', ['id' => $this->subscriber->id]),
        ];

        $content = $this->replacePlaceholders($template->body, $data);
        $subject = $this->replacePlaceholders($template->subject ?: "Renew Your Subscription - {$this->daysRemaining} Days Left", $data);

        return $this->subject($subject)->view('web.dynamic_email_template', compact('content'));
    }

    private function replacePlaceholders(?string $text, array $data): string
    {
        $content = (string) $text;
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', (string) $value, $content);
        }

        return $content;
    }
}
