<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('payment_reminder_settings')) {
            return;
        }

        Schema::create('payment_reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->enum('client_group', ['all', 'over_500', 'over_100'])->default('all');
            $table->enum('email_frequency', ['weekly', 'monthly', 'quarterly'])->default('weekly');
            $table->enum('email_to', ['client_only', 'client_bcc_subscriber'])->default('client_only');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reminder_settings');
    }
};
