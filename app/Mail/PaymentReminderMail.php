<?php

namespace App\Mail;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $payload;

    public function __construct($subscriber, array $payload)
    {
        $this->subscriber = $subscriber;
        $this->payload = $payload;
    }

    public function build()
    {
        $template = app(EmailTemplateService::class)->getTemplateForUser($this->subscriber, 'subscriber', 'payment_reminder');

        $defaultSubject = 'Reminder : Outstanding Payment (' . ($this->payload['subscriber_name'] ?? '') . ' - Invoice No ' . ($this->payload['invoice_no'] ?? '') . ')';
        $defaultBody = 'Dear {{client_first_name}},<br><br>' .
            'You have an outstanding to pay, settle the same to avoid interruptions in services, details of which is as below.<br><br>' .
            'Amount To Pay :- {{currency_symbol}} {{amount}}<br>' .
            'Invoice No :- {{invoice_no}}<br>' .
            'Service Description :- {{service_description}}<br>' .
            'Due Date :- {{payment_due_date}}';

        $subjectTemplate = $template?->subject ?: $defaultSubject;
        $bodyTemplate = $template?->body ?: $defaultBody;

        $subject = $this->replacePlaceholders($subjectTemplate, $this->payload);
        $content = $this->replacePlaceholders($bodyTemplate, $this->payload);

        return $this->subject($subject)->view('web.dynamic_email_template', compact('content'));
    }

    private function replacePlaceholders(?string $text, array $data): string
    {
        $content = (string) $text;
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', (string) $value, $content);
            $content = str_replace('<' . $key . '>', (string) $value, $content);
        }

        return $content;
    }
}
