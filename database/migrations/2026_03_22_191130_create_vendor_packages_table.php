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
            $table->foreignId('category_vendor_id')->nullable()->constrained('category_vendors')->nullOnDelete();
            $table->string('name');                         // Paket Silver, Gold, Platinum
            $table->unsignedBigInteger('price');            // 35000000 (integer, for sorting/calc/display)
            $table->unsignedBigInteger('discount')->default(0); // potongan harga (nominal)
            $table->unsignedBigInteger('dp_paket')->default(0); // DP Paket (nominal)
            $table->string('max_guests');                   // Maks. 300 tamu
            $table->string('card_color')->default('#C8D5B9');
            $table->string('card_text_color')->default('#444444');
            $table->longText('item')->nullable();           // HTML dari RichEditor
            $table->string('type')->nullable();             // Indoor/Outdoor/Semi Outdoor/Lainnya
            $table->integer('capacity')->nullable();        // Kapasitas venue (orang)
            $table->json('facilities')->nullable();         // Fasilitas venue
            $table->json('image_path')->nullable();         // Foto paket (multiple)
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
