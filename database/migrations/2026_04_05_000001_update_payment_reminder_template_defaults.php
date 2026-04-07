<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('email_templates')
            ->whereNull('owner_user_id')
            ->where('audience', 'subscriber')
            ->where('template_key', 'payment_reminder')
            ->update([
                'subject' => 'Outstanding Payment Reminder - {{client_name}} (Invoice {{invoice_no}})',
                'body' => '<p>Dear {{client_name}},</p><p>This is a friendly reminder for "Outstanding" payment for the invoice {{invoice_id}}.</p><p><strong>Application/Service :</strong> {{application_service}}<br><strong>Outstanding Amount :</strong> {{currency_symbol}} {{outstanding_amount}}<br><strong>Due Date :</strong> {{due_date}}<br><strong>Payment Link :</strong> {{payment_link_html}}</p><p>Please clear the outstanding amount to avoid interruption of services.</p><p>Sincerely,<br>{{subscriber_name}}</p>',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('email_templates')
            ->whereNull('owner_user_id')
            ->where('audience', 'subscriber')
            ->where('template_key', 'payment_reminder')
            ->update([
                'subject' => 'Payment Reminder',
                'body' => '<p>Hello {{name}},</p><p>This is your payment reminder.</p>',
                'updated_at' => now(),
            ]);
    }
};
