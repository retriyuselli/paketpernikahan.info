<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE vip_guests MODIFY COLUMN kategori ENUM(
            'vip',
            'keluarga_besar',
            'pejabat',
            'tokoh_masyarakat',
            'rekan_bisnis',
            'teman'
        ) DEFAULT 'vip'");

        // Ubah data lama yang 'teman' (default lama) menjadi 'vip'
        DB::table('vip_guests')->where('kategori', 'teman')->update(['kategori' => 'vip']);
    }

    public function down(): void
    {
        DB::table('vip_guests')->where('kategori', 'vip')->update(['kategori' => 'teman']);

        DB::statement("ALTER TABLE vip_guests MODIFY COLUMN kategori ENUM(
            'keluarga_besar',
            'pejabat',
            'tokoh_masyarakat',
            'rekan_bisnis',
            'teman'
        ) DEFAULT 'teman'");
    }
};
