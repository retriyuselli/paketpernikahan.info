<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_package_id')->nullable()->after('vendor_id');
            $table->foreign('vendor_package_id')->references('id')->on('vendor_packages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropForeign(['vendor_package_id']);
            $table->dropColumn('vendor_package_id');
        });
    }
};