<?php

namespace App\Mail;

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
        $mail = $this->subject('Welcome to adwiseri')
            ->view('web.welcometemplate', compact('data'));

        if (!empty($data->from_email)) {
            $mail->from($data->from_email, $data->from_name ?? null);
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
}
