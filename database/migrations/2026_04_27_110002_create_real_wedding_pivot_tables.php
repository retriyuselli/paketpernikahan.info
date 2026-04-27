<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('real_wedding_vendor', function (Blueprint $table) {
            $table->foreignId('real_wedding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->primary(['real_wedding_id', 'vendor_id']);
        });

        Schema::create('real_wedding_vendor_package', function (Blueprint $table) {
            $table->foreignId('real_wedding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_package_id')->constrained()->cascadeOnDelete();
            $table->primary(['real_wedding_id', 'vendor_package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('real_wedding_vendor_package');
        Schema::dropIfExists('real_wedding_vendor');
    }
};
