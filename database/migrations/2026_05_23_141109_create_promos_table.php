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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('description', 255)->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('fixed');
            $table->unsignedBigInteger('value');                         // Rp (fixed) atau % (percentage)
            $table->unsignedBigInteger('min_amount')->default(0);        // Minimal agreed_total sebelum promo
            $table->unsignedBigInteger('max_discount')->nullable();      // Batas potongan max (untuk percentage)
            $table->unsignedInteger('max_uses')->nullable();             // null = unlimited
            $table->unsignedInteger('uses_count')->default(0);
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
