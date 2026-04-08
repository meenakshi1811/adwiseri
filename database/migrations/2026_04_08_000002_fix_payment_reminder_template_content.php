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
            ->where('template_key', 'payment_reminder')
            ->update([
                'body' => DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(body, '\"Outstanding\"', 'outstanding'), '<strong>Payment Link :</strong> {{payment_link_html}}', '{{payment_link_section}}'), '<strong>Payment Link:</strong> {{payment_link_html}}', '{{payment_link_section}}'), 'Please clear the outstanding amount to avoid interruption of services.', 'Please clear the outstanding amount to avoid delays in service and/or late payment charges.')"),
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('email_templates')
            ->where('template_key', 'payment_reminder')
            ->update([
                'body' => DB::raw("REPLACE(REPLACE(REPLACE(REPLACE(body, 'outstanding payment for the invoice', '\"Outstanding\" payment for the invoice'), '{{payment_link_section}}', '<strong>Payment Link :</strong> {{payment_link_html}}'), 'Please clear the outstanding amount to avoid delays in service and/or late payment charges.', 'Please clear the outstanding amount to avoid interruption of services.'), 'friendly reminder for outstanding payment', 'friendly reminder for \"Outstanding\" payment')"),
                'updated_at' => now(),
            ]);
    }
};
