<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_infos', function (Blueprint $table) {
            $table->string('budaya', 100)->nullable()->after('bride_name');
            $table->json('songlist')->nullable()->after('budaya');
        });

        Schema::table('wedding_budgets', function (Blueprint $table) {
            $table->string('currency', 3)->default('IDR')->after('total_budget');
        });
    }

    public function down(): void
    {
        Schema::table('wedding_budgets', function (Blueprint $table) {
            $table->dropColumn('currency');
        });

        Schema::table('wedding_infos', function (Blueprint $table) {
            $table->dropColumn(['budaya', 'songlist']);
        });
    }
};
