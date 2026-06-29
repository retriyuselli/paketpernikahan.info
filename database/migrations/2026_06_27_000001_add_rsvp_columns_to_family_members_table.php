<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->enum('rsvp_status', ['menunggu', 'hadir', 'tidak_hadir'])
                ->default('menunggu')
                ->after('phone');
            $table->string('rsvp_updated_by_name', 150)->nullable()->after('rsvp_status');
            $table->timestamp('rsvp_updated_at')->nullable()->after('rsvp_updated_by_name');
        });
    }

    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn([
                'rsvp_status',
                'rsvp_updated_by_name',
                'rsvp_updated_at',
            ]);
        });
    }
};
