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
        // Hapus kolom FK lama sebelum membuat pivot table
        Schema::table('promos', function (Blueprint $table) {
            $table->dropForeign(['vendor_package_id']);
            $table->dropColumn('vendor_package_id');
        });

        Schema::create('promo_vendor_package', function (Blueprint $table) {
            $table->foreignId('promo_id')->constrained('promos')->cascadeOnDelete();
            $table->foreignId('vendor_package_id')->constrained('vendor_packages')->cascadeOnDelete();
            $table->primary(['promo_id', 'vendor_package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_vendor_package');

        Schema::table('promos', function (Blueprint $table) {
            $table->foreignId('vendor_package_id')->nullable()->constrained('vendor_packages')->nullOnDelete();
        });
    }
};
