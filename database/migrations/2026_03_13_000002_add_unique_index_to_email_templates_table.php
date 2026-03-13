<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("DELETE e1 FROM email_templates e1 INNER JOIN email_templates e2 WHERE e1.id < e2.id AND e1.owner_user_id = e2.owner_user_id AND e1.audience = e2.audience AND e1.template_key = e2.template_key AND e1.owner_user_id IS NOT NULL");

        Schema::table('email_templates', function (Blueprint $table) {
            $table->unique(['owner_user_id', 'audience', 'template_key'], 'email_templates_owner_audience_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropUnique('email_templates_owner_audience_key_unique');
        });
    }
};
