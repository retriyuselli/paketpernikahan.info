<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_ads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image');              // path di storage atau URL eksternal
            $table->string('caption')->nullable(); // teks kecil di bawah gambar
            $table->string('link_url')->nullable(); // klik pada modal → URL ini
            $table->string('link_label')->nullable(); // teks tombol CTA, mis. "Lihat Promo"
            $table->unsignedInteger('delay_seconds')->default(5); // delay tampil modal
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_ads');
    }
};
