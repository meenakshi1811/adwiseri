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
        $defaultBody = '<p>Dear {{client_name}},</p>' .
            '<p>This is a friendly reminder for outstanding payment for the invoice {{invoice_id}}.</p>' .
            '<p><strong>Application/Service :</strong> {{application_service}}<br>' .
            '<strong>Outstanding Amount :</strong> {{currency_symbol}} {{outstanding_amount}}<br>' .
            '<strong>Due Date :</strong> {{due_date}}</p>' .
            '{{payment_link_section}}' .
            '<p>Please clear the outstanding amount to avoid delays in service and/or late payment charges.</p>' .
            '<p>Sincerely,<br>{{subscriber_name}}</p>';

        $subjectTemplate = $template?->subject ?: $defaultSubject;
        $bodyTemplate = $template?->body ?: $defaultBody;
        $resolvedPayload = $this->withDynamicAliases($this->payload);

        $subject = $this->replacePlaceholders($subjectTemplate, $resolvedPayload);
        $content = $this->replacePlaceholders($bodyTemplate, $resolvedPayload);

        $headerTitle = 'Outstanding Payment Reminder';

        return $this->subject($subject)->view('web.dynamic_email_template', compact('content', 'headerTitle'));
    }

    private function replacePlaceholders(?string $text, array $data): string
    {
        $content = (string) $text;
        foreach ($data as $key => $value) {
            $quotedKey = preg_quote((string) $key, '/');
            $content = preg_replace('/{{\s*' . $quotedKey . '\s*}}/i', (string) $value, $content);
            $content = preg_replace('/<\s*' . $quotedKey . '\s*>/i', (string) $value, $content);
        }

        return $this->removeEmptyPaymentLinkLine($content);
    }

    private function removeEmptyPaymentLinkLine(string $content): string
    {
        if (stripos($content, 'Payment Link') === false) {
            return $content;
        }

        $patterns = [
            '/<br\s*\/?>\s*<strong>\s*Payment Link\s*:?\s*<\/strong>\s*(?:<a[^>]*>\s*<\/a>\s*)?(?=<br\s*\/?>|<\/p>)/i',
            '/<strong>\s*Payment Link\s*:?\s*<\/strong>\s*(?:<a[^>]*>\s*<\/a>\s*)?(?:<br\s*\/?>)?/i',
            '/<p>\s*<strong>\s*Payment Link\s*:\s*<\/strong>\s*(?:<a[^>]*>\s*<\/a>\s*)?<\/p>/i',
            '/<p>\s*Payment Link\s*:\s*(?:<a[^>]*>\s*<\/a>\s*)?<\/p>/i',
            '/^\s*Payment Link\s*:\s*$/im',
        ];

        return trim((string) preg_replace($patterns, '', $content));
    }

    private function withDynamicAliases(array $data): array
    {
        $amount = $data['outstanding_amount'] ?? $data['amount'] ?? '';
        $clientName = $data['client_name'] ?? $data['client_first_name'] ?? $data['name'] ?? '';
        $dueDate = $data['due_date'] ?? $data['payment_due_date'] ?? '';
        $applicationService = $data['application_service'] ?? $data['service_description'] ?? '';
        $paymentLink = trim((string) ($data['payment_link'] ?? ''));
        $hasPaymentLink = filter_var($paymentLink, FILTER_VALIDATE_URL);
        $paymentLinkHtml = $hasPaymentLink
            ? '<a href="' . e($paymentLink) . '" target="_blank" rel="noopener noreferrer">Pay Now</a>'
            : '';
        $paymentLinkSection = $hasPaymentLink
            ? '<p><strong>Payment Link :</strong> ' . $paymentLinkHtml . '</p>'
            : '';
        $subscriberName = $data['subscriber_name'] ?? $data['subscriber_display_name'] ?? '';

        return array_merge($data, [
            'name' => $clientName,
            'client_name' => $clientName,
            'client_first_name' => $data['client_first_name'] ?? $clientName,
            'amount' => $amount,
            'outstanding_amount' => $amount,
            'payment_due_date' => $dueDate,
            'due_date' => $dueDate,
            'application_service' => $applicationService,
            'service_description' => $applicationService,
            'payment_link' => $paymentLink,
            'payment_link_html' => $data['payment_link_html'] ?? $paymentLinkHtml,
            'payment_link_section' => $data['payment_link_section'] ?? $paymentLinkSection,
            'subscriber_name' => $subscriberName,
            'subscriber_display_name' => $subscriberName,
        ]);
    }
}
