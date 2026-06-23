<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('jenis_acara', ['lamaran', 'pengajian', 'akad', 'resepsi']);
            $table->date('tgl_acara')->nullable();
            $table->string('lokasi_acara', 200)->nullable();
            $table->foreignId('vendor_booking_id')
                  ->nullable()
                  ->constrained('vendor_bookings')
                  ->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_events');
    }
};
