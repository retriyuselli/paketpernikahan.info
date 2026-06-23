<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vip_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('jabatan', 150)->nullable();
            $table->string('instansi', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->enum('kategori', [
                'keluarga_besar',
                'pejabat',
                'tokoh_masyarakat',
                'rekan_bisnis',
                'teman',
            ])->default('teman');
            $table->enum('rsvp_status', ['menunggu', 'hadir', 'tidak_hadir'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vip_guests');
    }
};
