<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('internal_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('internal_invoices', 'uploaded_invoice')) {
                $table->string('uploaded_invoice')->nullable()->after('token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('internal_invoices', 'uploaded_invoice')) {
                $table->dropColumn('uploaded_invoice');
            }
        });
    }
};
