<?php

namespace App\Mail;

use App\Services\EmailTemplateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    public function __construct($maildata)
    {
        $this->data = $maildata;
    }

    public function build()
    {
        $data = $this->data;
        $templateService = app(EmailTemplateService::class);
        $owner = $templateService->resolveTemplateOwner($data);
        $template = $templateService->getTemplateForUser($owner, 'admin', 'welcome_email_admin_to_subscriber');

        $defaultSubject = 'Welcome to adwiseri';
        $mail = $this->subject($defaultSubject);

        if ($template && !empty(trim((string) $template->body)) && $this->isPaidSubscriptionMail($data)) {
            $payload = $this->buildPlaceholderData($data);
            $content = $this->replacePlaceholders($template->body, $payload);
            $subject = $this->replacePlaceholders($template->subject ?: $defaultSubject, $payload);

            $mail = $mail->subject($subject)->view('web.welcometemplate', compact('data', 'content'));
        } else {
            $mail = $mail->view('web.welcometemplate', compact('data'));
        }

        if (!empty($data->from_email)) {
            $mail->from($data->from_email, $this->sanitizeFromNameForWelcome($data->from_name ?? null));
        }

        if (!empty($data->invoice_pdf_data)) {
            $invoiceData = is_array($data->invoice_pdf_data)
                ? (object) $data->invoice_pdf_data
                : $data->invoice_pdf_data;

            $pdf = Pdf::loadView('web.invoice_pdf', ['data' => $invoiceData])
                ->setPaper('a4', 'portrait')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isPhpEnabled', true);
            $invoiceNo = $invoiceData->invoice_no ?? 'document';
            $mail->attachData($pdf->output(), 'Invoice-' . $invoiceNo . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }

    private function isPaidSubscriptionMail($data): bool
    {
        $map = is_array($data) ? $data : (array) $data;

        return strtolower((string) ($map['subscription'] ?? '')) === 'paid';
    }

    private function buildPlaceholderData($data): array
    {
        $map = is_array($data) ? $data : (array) $data;

        if (empty($map['invoice_link']) && !empty($map['invoice_id']) && !empty($map['token'])) {
            $map['invoice_link'] = route('invoice_preview', $map['invoice_id'] . '/' . $map['token']);
        }

        if (empty($map['subscription_type']) && !empty($map['plan_name'])) {
            $map['subscription_type'] = $map['plan_name'];
        }

        $map['start_date'] = $map['start_date'] ?? '-';
        $map['end_date'] = $map['end_date'] ?? '-';
        $map['paid_amount'] = $map['paid_amount'] ?? ($map['amount'] ?? '0.00');
        $map['invoice_link'] = $map['invoice_link'] ?? '#';

        return $map;
    }

    private function replacePlaceholders(?string $text, array $data): string
    {
        $content = (string) $text;

        foreach ($data as $key => $value) {
            if (!is_scalar($value) && !is_null($value)) {
                continue;
            }

            $content = preg_replace('/{{\s*' . preg_quote((string) $key, '/') . '\s*}}/', (string) $value, $content);
        }

        return preg_replace('/{{\s*[A-Za-z0-9_]+\s*}}/', '-', $content);
    }

    private function sanitizeFromNameForWelcome(?string $fromName): ?string
    {
        if ($fromName === null) {
            return null;
        }

        $trimmed = trim($fromName);

        if ($trimmed === '') {
            return null;
        }

        return preg_replace('/\s*-\s*Alert\s*$/i', '', $trimmed);
    }
}
