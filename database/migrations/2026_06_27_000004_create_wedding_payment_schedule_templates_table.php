<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_payment_schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_acara', ['lamaran', 'pengajian', 'akad', 'resepsi']);
            $table->string('title');
            $table->string('vendor_name');
            $table->enum('category', [
                'venue',
                'catering',
                'decoration',
                'photo_video',
                'entertainment',
                'makeup',
                'transport',
                'wo',
                'other',
            ])->default('other');
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedSmallInteger('due_days_before_event')->default(30);
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_payment_schedule_templates');
    }
};
