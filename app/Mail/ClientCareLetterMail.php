<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientCareLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    protected $attachmentPath;

    public function __construct(array $data, string $attachmentPath)
    {
        $this->data = $data;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        $subscriberName = $this->data['subscriber']->name ?? 'Subscriber';
        $subscriberEmail = $this->data['subscriber']->email ?? null;
        $isClientCareLetter = ($this->data['letter_type'] ?? null) === 'oisc_iaa';
        $subject = $isClientCareLetter ? 'Client Care Letter' : 'Service Agreement';

        $mail = $this->subject($subject)
            ->from('alerts@adwiseri.com', 'Sent on behalf of ' . $subscriberName)
            ->view('web.client_care_letter_email', ['data' => $this->data])
            ->attach($this->attachmentPath, [
                'as' => str_replace(' ', '-', $this->data['document_title']) . '.pdf',
                'mime' => 'application/pdf',
            ]);

        if (!empty($subscriberEmail)) {
            $mail->replyTo($subscriberEmail, $subscriberName)
                ->cc($subscriberEmail);
        }

        return $mail;
    }
}
