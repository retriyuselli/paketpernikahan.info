<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_booking_id')->constrained('vendor_bookings')->cascadeOnDelete();
            $table->string('type', 20)->default('dp');
            $table->unsignedBigInteger('amount');
            $table->string('method', 20)->default('transfer');
            $table->string('sender_name', 120)->nullable();
            $table->string('sender_bank', 80)->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('proof_path', 255)->nullable();
            $table->string('status', 30)->default('pending_verification');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_booking_payments');
    }
};

