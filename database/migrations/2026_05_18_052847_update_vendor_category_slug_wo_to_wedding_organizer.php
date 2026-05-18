<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('vendors')
            ->where('category', 'wo')
            ->update(['category' => 'wedding-organizer']);
    }

    public function down(): void
    {
        DB::table('vendors')
            ->where('category', 'wedding-organizer')
            ->update(['category' => 'wo']);
    }
};
