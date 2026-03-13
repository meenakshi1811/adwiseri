<?php

namespace App\Mail;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
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

        $templateKey = 'otp_email';
        $defaultSubject = 'adwiseri Email Verification';

        if (isset($data->password)) {
            $templateKey = 'forgot_password_email';
            $defaultSubject = 'adwiseri Password Recovery OTP';
        } elseif (isset($data->message)) {
            $templateKey = 'contact_us_notification_email';
            $defaultSubject = 'New Message from adwiseri.com (Contact Us)';
        } elseif (isset($data->how_did_hear)) {
            $templateKey = 'demo_request_notification_email';
            $defaultSubject = 'Demo Request from adwiseri.com';
        }

        $owner = $templateService->resolveTemplateOwner($data);

       
        $template = $templateService->getTemplateForUser($owner, 'admin', $templateKey);
    //    echo'<pre>';print_r($template);echo'</pre>';exit;
        if (!$template) {
            return $this->subject($defaultSubject)->view('web.emailtemplate', compact('data'));
        }

        $content = $this->replacePlaceholders($template->body, $data);
        $subject = $this->replacePlaceholders($template->subject ?: $defaultSubject, $data);

        return $this->subject($subject)->view('web.dynamic_email_template', compact('content'));
    }

    private function replacePlaceholders(?string $text, $data): string
    {
        $content = (string) $text;
        $map = [];
        if (is_array($data)) {
            $map = $data;
        } elseif (is_object($data)) {
            $map = (array) $data;
        }

        foreach ($map as $key => $value) {
            if (is_scalar($value) || is_null($value)) {
                $content = str_replace('{{' . $key . '}}', (string) $value, $content);
            }
        }

        return $content;
    }
}
