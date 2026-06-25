<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vip_guests', function (Blueprint $table) {
            $table->string('rsvp_updated_by_name', 150)->nullable()->after('rsvp_status');
            $table->timestamp('rsvp_updated_at')->nullable()->after('rsvp_updated_by_name');
        });
    }

    public function down(): void
    {
        Schema::table('vip_guests', function (Blueprint $table) {
            $table->dropColumn(['rsvp_updated_by_name', 'rsvp_updated_at']);
        });
    }
};
