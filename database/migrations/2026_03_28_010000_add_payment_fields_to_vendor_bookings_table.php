<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('agreed_total')->nullable()->after('vendor_package_id');
            $table->unsignedBigInteger('dp_required_amount')->nullable()->after('agreed_total');
            $table->string('payment_status', 30)->default('unpaid')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_bookings', function (Blueprint $table) {
            $table->dropColumn(['agreed_total', 'dp_required_amount', 'payment_status']);
        });
    }
};

