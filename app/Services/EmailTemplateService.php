<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\User;

class EmailTemplateService
{
    public const ADMIN_TEMPLATE_KEYS = [
        'otp_email',
        'forgot_password_email',
        'welcome_email_admin_to_subscriber',
        'demo_request_notification_email',
        'contact_us_notification_email',
        'support_ticket_notification_email',
    ];

    public const SUBSCRIBER_TEMPLATE_KEYS = [
        'newsletter',
        'payment_reminder',
        'subscription_expiry_reminder',
        'subscription_termination',
        'wallet_credit_alert',
        'wallet_debit_alert',
        'reports',
        'other',
    ];

    public function getTemplateForUser(?User $owner, string $audience, string $templateKey): ?EmailTemplate
    {
        $ownerId = $owner?->id;

        if ($ownerId) {
            $custom = EmailTemplate::where('owner_user_id', $ownerId)
                ->where('audience', $audience)
                ->where('template_key', $templateKey)
                ->first();

            if ($custom && !empty(trim((string) $custom->body))) {
                return $custom;
            }
        }

        return EmailTemplate::whereNull('owner_user_id')
            ->where('audience', $audience)
            ->where('template_key', $templateKey)
            ->first();
    }

    public function getTemplatesForSettings(?User $owner): array
    {
        $ownerId = $owner?->id;

        $defaults = EmailTemplate::whereNull('owner_user_id')->get()->keyBy(function ($row) {
            return $row->audience . '::' . $row->template_key;
        });

        $custom = collect();
        if ($ownerId) {
            $custom = EmailTemplate::where('owner_user_id', $ownerId)->get()->keyBy(function ($row) {
                return $row->audience . '::' . $row->template_key;
            });
        }

        $merged = $defaults->merge($custom);

        return [
            'admin' => $merged->filter(fn($t) => $t->audience === 'admin')->values(),
            'subscriber' => $merged->filter(fn($t) => $t->audience === 'subscriber')->values(),
        ];
    }

    public function saveTemplate(User $owner, array $payload): EmailTemplate
    {
        return EmailTemplate::updateOrCreate(
            [
                'owner_user_id' => $owner->id,
                'audience' => $payload['audience'],
                'template_key' => $payload['template_key'],
            ],
            [
                'template_name' => $payload['template_name'],
                'custom_name' => $payload['custom_name'] ?? null,
                'subject' => $payload['subject'] ?? null,
                'body' => $payload['body'] ?? null,
            ]
        );
    }
}
