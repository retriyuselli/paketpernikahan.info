<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_payment_schedules', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->foreignId('customer_payment_method_id')
                ->nullable()
                ->after('paid_at')
                ->constrained('customer_payment_methods')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('wedding_payment_schedules', function (Blueprint $table) {
            $table->dropForeign(['customer_payment_method_id']);
            $table->dropColumn('customer_payment_method_id');
            $table->string('payment_method')->nullable()->after('paid_at');
        });
    }
};
