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
        Schema::table('promos', function (Blueprint $table) {
            $table->foreignId('vendor_package_id')
                ->nullable()
                ->after('vendor_id')
                ->constrained('vendor_packages')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropForeign(['vendor_package_id']);
            $table->dropColumn('vendor_package_id');
        });
    }
};
