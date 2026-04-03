<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendor_package_id')->nullable()->constrained('vendor_packages')->nullOnDelete();
            $table->unsignedBigInteger('agreed_total')->nullable();
            $table->unsignedBigInteger('dp_required_amount')->nullable();
            $table->date('event_date');
            $table->string('phone', 30);
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('payment_status', 30)->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_bookings');
    }
};
