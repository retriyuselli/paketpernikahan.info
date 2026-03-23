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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');                          // e.g. Gedung Wedding Venue
            $table->string('category');                      // gedung | hotel | rumah | wo
            $table->string('location');
            $table->string('province')->nullable();
            $table->string('city')->default('Palembang');
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('instagram')->nullable();
            $table->string('capacity')->nullable();          // e.g. 500 – 1.500 tamu
            $table->string('price_start')->nullable();       // e.g. Rp 35.000.000
            $table->string('price_start_raw')->nullable();   // numeric for sorting
            $table->string('experience')->nullable();        // e.g. 10+ Tahun
            $table->string('venue_type')->nullable();        // Indoor & Outdoor
            $table->string('facilities')->nullable();        // AC, Parkir, Lift
            $table->integer('events_done')->default(0);
            $table->unsignedSmallInteger('likes')->default(0);
            $table->unsignedSmallInteger('comments_count')->default(0);
            $table->decimal('rating', 2, 1)->nullable();
            $table->text('badge')->nullable();               // terlaris | top_rated | unggulan | verified
            $table->text('promo')->nullable();               // diskon | flash_sale | early_bird dll
            $table->string('cover_image')->nullable();       // main hero photo path
            $table->string('cover_video')->nullable();       // YouTube URL for cover video
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
