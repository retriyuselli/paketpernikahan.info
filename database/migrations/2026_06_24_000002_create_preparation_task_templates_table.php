<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preparation_task_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_acara', ['lamaran', 'pengajian', 'akad', 'resepsi']);
            $table->string('label', 100);    // kategori, misal "Venue", "Dokumen Nikah"
            $table->string('title', 200);    // nama tugas
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preparation_task_templates');
    }
};
