<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('report_dispatch_logs')) {
            return;
        }

        Schema::create('report_dispatch_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_setting_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('frequency')->nullable();
            $table->string('delivery_mode')->nullable();
            $table->string('modules_hash')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('file_name')->nullable();
            $table->text('recipients')->nullable();
            $table->string('status')->nullable();
            $table->string('triggered_by')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['report_setting_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->unique(['report_setting_id', 'period_start', 'period_end', 'modules_hash', 'delivery_mode'], 'report_dispatch_unique_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_dispatch_logs');
    }
};
