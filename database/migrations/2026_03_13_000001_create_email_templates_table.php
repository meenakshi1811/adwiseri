<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->string('audience', 30);
            $table->string('template_key', 100);
            $table->string('template_name', 191);
            $table->string('custom_name', 191)->nullable();
            $table->string('subject', 191)->nullable();
            $table->longText('body')->nullable();
            $table->timestamps();

            $table->index(['owner_user_id', 'audience', 'template_key'], 'email_templates_owner_audience_key_idx');
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
