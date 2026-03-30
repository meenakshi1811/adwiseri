<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
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
    public $invoicePdfData;

    // Constructor to pass the subscriber's name and the updated plan details
    public function __construct($subscriberName, $planDetails, $validityDuration, $title, $invoicePdfData = null)
    {
        $this->subscriberName = $subscriberName;
        $this->planDetails = $planDetails;
        $this->validityDuration = $validityDuration;
        $this->title = $title;
        $this->invoicePdfData = $invoicePdfData;
    }

    // Build the message
    public function build()
    {
        $mail = $this->subject($this->title)
                    ->view('web.new_subscriptiontemplate')
                    ->with([
                        'subscriberName' => $this->subscriberName,
                        'planDetails' => $this->planDetails,
                        'validityDuration' => $this->validityDuration,
                        'title'=>$this->title
                    ]);

        if (!empty($this->invoicePdfData)) {
            $invoiceData = is_array($this->invoicePdfData)
                ? (object) $this->invoicePdfData
                : $this->invoicePdfData;

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
