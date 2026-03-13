<?php

namespace App\Mail;

use App\Services\EmailTemplateService;
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

        if (!$template) {
            return $this->subject('Welcome to adwiseri')->view('web.welcometemplate', compact('data'));
        }

        $content = $this->replacePlaceholders($template->body, $data);
        $subject = $this->replacePlaceholders($template->subject ?: 'Welcome to adwiseri', $data);

        return $this->subject($subject)->view('web.dynamic_email_template', compact('content'));
    }

    private function replacePlaceholders(?string $text, $data): string
    {
        $content = (string) $text;
        $map = is_object($data) ? (array) $data : (array) $data;

        foreach ($map as $key => $value) {
            if (is_scalar($value) || is_null($value)) {
                $content = str_replace('{{' . $key . '}}', (string) $value, $content);
            }
        }

        return $content;
    }
}
