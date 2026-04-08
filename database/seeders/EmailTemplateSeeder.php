<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['audience' => 'admin', 'template_key' => 'otp_email', 'template_name' => 'OTP Email', 'subject' => 'adwiseri OTP', 'body' => '<p><strong>Hello {{name}}</strong></p><p>Your OTP is <strong>{{otp}}</strong>.</p>'],
            ['audience' => 'admin', 'template_key' => 'forgot_password_email', 'template_name' => 'Forgot Password Email', 'subject' => 'adwiseri Password Recovery OTP', 'body' => '<p><strong>Hello {{name}}</strong></p><p>Your password recovery OTP is <strong>{{otp}}</strong>.</p>'],
            ['audience' => 'admin', 'template_key' => 'welcome_email_admin_to_subscriber', 'template_name' => 'Welcome Email (Admin to Subscriber version)', 'subject' => 'Welcome to adwiseri', 'body' => '<p><strong>Hello {{name}}</strong></p><p>Welcome to adwiseri. Your registration is successful.</p>'],
            ['audience' => 'admin', 'template_key' => 'demo_request_notification_email', 'template_name' => 'Demo Request Email Notification Email', 'subject' => 'Demo Request from adwiseri.com', 'body' => '<p>A demo request was submitted on adwiseri.com.</p>'],
            ['audience' => 'admin', 'template_key' => 'contact_us_notification_email', 'template_name' => 'Contact Us Form Data Notification Email', 'subject' => 'New Message from adwiseri.com (Contact Us)', 'body' => '<p>New contact form message received from {{name}} ({{email}}).</p><p>{{message}}</p>'],
            ['audience' => 'admin', 'template_key' => 'support_ticket_notification_email', 'template_name' => 'Support Ticket Notification Email', 'subject' => 'New Support Ticket Raised ({{ticket_id}})', 'body' => '<p>A new support ticket has been raised.</p><p>Ticket ID: {{ticket_id}}</p><p>Issue: {{issue}}</p>'],
            ['audience' => 'subscriber', 'template_key' => 'newsletter', 'template_name' => 'Newsletter', 'subject' => 'Newsletter', 'body' => '<p>Hello {{name}},</p><p>{{message}}</p>'],
            ['audience' => 'subscriber', 'template_key' => 'payment_reminder', 'template_name' => 'Payment Reminder', 'subject' => 'Outstanding Payment Reminder - {{client_name}} (Invoice {{invoice_no}})', 'body' => '<p>Dear {{client_name}},</p><p>This is a friendly reminder for outstanding payment for the invoice {{invoice_id}}.</p><p><strong>Application/Service :</strong> {{application_service}}<br><strong>Outstanding Amount :</strong> {{currency_symbol}} {{outstanding_amount}}<br><strong>Due Date :</strong> {{due_date}}</p>{{payment_link_section}}<p>Please clear the outstanding amount to avoid delays in service and/or late payment charges.</p><p>Sincerely,<br>{{subscriber_name}}</p>'],
            ['audience' => 'subscriber', 'template_key' => 'subscription_expiry_reminder', 'template_name' => 'Subscription Expiry Reminder', 'subject' => 'Subscription Expiry Reminder', 'body' => '<p>Hello {{name}},</p><p>Your subscription expires in {{daysRemaining}} days.</p>'],
            ['audience' => 'subscriber', 'template_key' => 'subscription_termination', 'template_name' => 'Subscription Termination', 'subject' => 'Subscription Termination', 'body' => '<p>Hello {{name}},</p><p>Your subscription has been terminated.</p>'],
            ['audience' => 'subscriber', 'template_key' => 'wallet_credit_alert', 'template_name' => 'Wallet Credit Alert', 'subject' => 'Wallet Credit Alert', 'body' => '<p>Hello {{name}},</p><p>Your wallet was credited.</p>'],
            ['audience' => 'subscriber', 'template_key' => 'wallet_debit_alert', 'template_name' => 'Wallet Debit Alert', 'subject' => 'Wallet Debit Alert', 'body' => '<p>Hello {{name}},</p><p>Your wallet was debited.</p>'],
            ['audience' => 'subscriber', 'template_key' => 'reports', 'template_name' => 'Reports', 'subject' => 'Reports', 'body' => '<p>Hello {{name}},</p><p>Your report is ready.</p>'],
            ['audience' => 'subscriber', 'template_key' => 'other', 'template_name' => 'Other', 'subject' => 'Notification', 'body' => '<p>Hello {{name}},</p><p>{{message}}</p>'],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                [
                    'owner_user_id' => null,
                    'audience' => $template['audience'],
                    'template_key' => $template['template_key'],
                ],
                $template
            );
        }
    }
}
