<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('label_persiapans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_acara', ['lamaran', 'pengajian', 'akad', 'resepsi']);
            $table->string('nama', 100);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['jenis_acara', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('label_persiapans');
    }
};
