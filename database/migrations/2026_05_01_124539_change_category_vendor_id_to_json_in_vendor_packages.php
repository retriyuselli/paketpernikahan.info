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
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->dropForeign(['category_vendor_id']);
            $table->json('category_vendor_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('category_vendor_id')->nullable()->change();
            $table->foreign('category_vendor_id')->references('id')->on('category_vendors')->nullOnDelete();
        });
    }
};
