<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Invoicemail extends Mailable
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

        $pdf = Pdf::loadView('web.invoice_pdf', compact('data'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $fileName = 'Invoice-' . ($data->invoice_no ?? 'document') . '.pdf';

        $mail = $this->subject('New Invoice');

        if (!empty($data->from_email)) {
            $mail->from($data->from_email, $data->from_name ?? null);
        }

        if (!empty($data->reply_to_email)) {
            $mail->withSymfonyMessage(function ($message) {
                $message->getHeaders()->remove('Reply-To');
            });
            $mail->replyTo($data->reply_to_email, $data->reply_to_name ?? null);
        }

        return $mail->view('web.invoicetemplate', compact('data'))
            ->attachData($pdf->output(), $fileName, [
                'mime' => 'application/pdf',
            ]);
    }
}
