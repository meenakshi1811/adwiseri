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
        Schema::table('offers', function (Blueprint $table) {
            if (!Schema::hasColumn('offers', 'subscriber_type')) {
                $table->string('subscriber_type')->default('existing')->after('discount_value');
            }

            if (!Schema::hasColumn('offers', 'offer_start_date')) {
                $table->date('offer_start_date')->nullable()->after('subscriber_type');
            }

            if (!Schema::hasColumn('offers', 'offer_end_date')) {
                $table->date('offer_end_date')->nullable()->after('offer_start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('offers', 'offer_end_date')) {
                $dropColumns[] = 'offer_end_date';
            }

            if (Schema::hasColumn('offers', 'offer_start_date')) {
                $dropColumns[] = 'offer_start_date';
            }

            if (Schema::hasColumn('offers', 'subscriber_type')) {
                $dropColumns[] = 'subscriber_type';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
