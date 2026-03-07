<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('report_settings')) {
            return;
        }

        Schema::table('report_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('report_settings', 'last_sent_at')) {
                $table->timestamp('last_sent_at')->nullable()->after('emails');
            }
            if (!Schema::hasColumn('report_settings', 'last_sent_status')) {
                $table->string('last_sent_status')->nullable()->after('last_sent_at');
            }
            if (!Schema::hasColumn('report_settings', 'last_sent_message')) {
                $table->text('last_sent_message')->nullable()->after('last_sent_status');
            }
            if (!Schema::hasColumn('report_settings', 'last_file_name')) {
                $table->string('last_file_name')->nullable()->after('last_sent_message');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('report_settings')) {
            return;
        }

        Schema::table('report_settings', function (Blueprint $table) {
            foreach (['last_sent_at', 'last_sent_status', 'last_sent_message', 'last_file_name'] as $column) {
                if (Schema::hasColumn('report_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
