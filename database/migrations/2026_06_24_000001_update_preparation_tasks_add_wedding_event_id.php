<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_preparation_tasks', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->unsignedBigInteger('section_id')->nullable()->change();
            $table->foreign('section_id')
                  ->references('id')
                  ->on('customer_preparation_sections')
                  ->cascadeOnDelete();

            $table->foreignId('wedding_event_id')
                  ->nullable()
                  ->after('section_id')
                  ->constrained('wedding_events')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customer_preparation_tasks', function (Blueprint $table) {
            $table->dropForeign(['wedding_event_id']);
            $table->dropColumn('wedding_event_id');

            $table->dropForeign(['section_id']);
            $table->unsignedBigInteger('section_id')->nullable(false)->change();
            $table->foreign('section_id')
                  ->references('id')
                  ->on('customer_preparation_sections')
                  ->cascadeOnDelete();
        });
    }
};
