<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_payment_schedules', function (Blueprint $table) {
            $table->foreignId('wedding_event_id')
                ->nullable()
                ->after('user_id')
                ->constrained('wedding_events')
                ->cascadeOnDelete();

            $table->foreignId('source_template_id')
                ->nullable()
                ->after('wedding_event_id')
                ->constrained('wedding_payment_schedule_templates')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wedding_payment_schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('source_template_id');
            $table->dropConstrainedForeignId('wedding_event_id');
        });
    }
};
