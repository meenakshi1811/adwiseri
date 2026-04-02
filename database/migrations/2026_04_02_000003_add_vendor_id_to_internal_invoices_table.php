<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('internal_invoices', 'vendor_id')) {
                $table->string('vendor_id')->nullable()->after('invoice_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('internal_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('internal_invoices', 'vendor_id')) {
                $table->dropColumn('vendor_id');
            }
        });
    }
};
