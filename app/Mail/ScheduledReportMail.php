<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduledReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $mailData,
        public string $filePath,
        public string $fileName
    ) {
    }

    public function build()
    {
        return $this->subject($this->mailData['subject'])
            ->view('web.scheduled_report_email')
            ->with(['data' => $this->mailData])
            ->attach($this->filePath, [
                'as' => $this->fileName,
                'mime' => 'application/pdf',
            ]);
    }
}

