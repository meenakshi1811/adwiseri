<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportMail extends Mailable
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
        $owner = !empty($data->subscriber_id) ? User::find($data->subscriber_id) : null;
        $template = app(EmailTemplateService::class)->getTemplateForUser($owner, 'admin', 'support_ticket_notification_email');

        if (!$template) {
            if ($data->attachment) {
                return $this->subject('New Support Ticket Raised(' . $data->ticket_id . ')')
                    ->view('web.supporttemplate', compact('data'))
                    ->attach('web_assets/users/ticket_images/' . $data->attachment);
            }

            return $this->subject('New Support Ticket Raised(' . $data->ticket_id . ')')->view('web.supporttemplate', compact('data'));
        }

        $content = $this->replacePlaceholders($template->body, $data);
        $subject = $this->replacePlaceholders($template->subject ?: 'New Support Ticket Raised(' . $data->ticket_id . ')', $data);
        $mail = $this->subject($subject)->view('web.dynamic_email_template', compact('content'));

        if ($data->attachment) {
            $mail->attach('web_assets/users/ticket_images/' . $data->attachment);
        }

        return $mail;
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
