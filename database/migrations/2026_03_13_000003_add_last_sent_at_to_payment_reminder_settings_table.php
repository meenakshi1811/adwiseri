<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payment_reminder_settings')) {
            return;
        }

        Schema::table('payment_reminder_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_reminder_settings', 'last_sent_at')) {
                $table->timestamp('last_sent_at')->nullable()->after('email_to');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('payment_reminder_settings')) {
            return;
        }

        Schema::table('payment_reminder_settings', function (Blueprint $table) {
            if (Schema::hasColumn('payment_reminder_settings', 'last_sent_at')) {
                $table->dropColumn('last_sent_at');
            }
        });
    }
};
