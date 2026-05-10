<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_ads', function (Blueprint $table) {
            $table->enum('type', ['card', 'banner'])->default('card')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('home_ads', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
