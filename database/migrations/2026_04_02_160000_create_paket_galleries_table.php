<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_package_id')->constrained('vendor_packages')->cascadeOnDelete();
            $table->string('video_url')->nullable();
            $table->string('image_video')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_galleries');
    }
};
