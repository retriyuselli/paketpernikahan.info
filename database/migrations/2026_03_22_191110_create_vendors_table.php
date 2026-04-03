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
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category');                      // gedung | hotel | rumah | wo
            $table->json('categories')->nullable();
            $table->string('location');
            $table->string('province')->nullable();
            $table->string('city')->default('Palembang');
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('instagram')->nullable();
            $table->unsignedBigInteger('price_start')->nullable();
            $table->unsignedInteger('discount')->default(0);     // potongan harga (nominal)
            $table->unsignedInteger('experience')->default(0);   // pengalaman (tahun)
            $table->integer('events_done')->default(0);
            $table->unsignedSmallInteger('likes')->default(0);
            $table->unsignedSmallInteger('comments_count')->default(0);
            $table->decimal('rating', 2, 1)->nullable();
            $table->text('badge')->nullable();               // terlaris | top_rated | unggulan | verified
            $table->text('promo')->nullable();               // diskon | flash_sale | early_bird dll
            $table->text('cover_image')->nullable();         // main hero photo path
            $table->string('logo_vendor', 255)->nullable();
            $table->string('cover_video')->nullable();       // YouTube URL for cover video
            $table->boolean('is_active')->default(true);
            $table->boolean('is_profile_complete')->default(false);
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
