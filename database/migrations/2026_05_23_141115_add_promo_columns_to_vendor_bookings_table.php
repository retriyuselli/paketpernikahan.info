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
        Schema::table('vendor_bookings', function (Blueprint $table) {
            $table->string('promo_code', 50)->nullable()->after('notes');
            $table->unsignedBigInteger('promo_discount')->nullable()->after('promo_code');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_bookings', function (Blueprint $table) {
            $table->dropColumn(['promo_code', 'promo_discount']);
        });
    }
};
