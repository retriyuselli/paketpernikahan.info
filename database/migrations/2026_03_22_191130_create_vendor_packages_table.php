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
        Schema::create('vendor_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('name');                         // Paket Silver, Gold, Platinum
            $table->string('price');                        // Rp 35.000.000 (display)
            $table->unsignedBigInteger('price_raw');        // 35000000 (for sorting/calc)
            $table->unsignedBigInteger('discount')->default(0); // potongan harga (nominal)
            $table->string('max_guests');                   // Maks. 300 tamu
            $table->string('card_color')->default('#C8D5B9');
            $table->string('card_text_color')->default('#444444');
            $table->json('items');                          // ["Gedung 6 jam", ...]
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_packages');
    }
};
