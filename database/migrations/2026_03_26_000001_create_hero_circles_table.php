<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_circles', function (Blueprint $table) {
            $table->id();
            $table->string('image_url');
            $table->string('alt');
            $table->unsignedSmallInteger('size_px')->default(64);   // 56=w-14, 64=w-16, 72=w-18, 80=w-20
            $table->string('color_from', 20)->default('#9CAF88');   // hex gradient start
            $table->string('color_to', 20)->default('#C8D5B9');     // hex gradient end
            $table->decimal('animation_delay', 4, 1)->default(0);
            $table->decimal('animation_duration', 4, 1)->default(20);
            $table->string('position_side', 5)->default('left');    // 'left' or 'right'
            $table->string('position_x', 10)->default('50%');       // e.g. '5%', '15%'
            $table->string('position_bottom', 10)->default('0px');  // e.g. '-80px', '20px'
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_circles');
    }
};
