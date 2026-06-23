<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('group', 60)->nullable();
            $table->string('title', 150);
            $table->text('message');
            $table->string('icon', 80)->nullable();
            $table->string('destination', 100)->nullable();
            $table->string('tint', 20)->nullable();
            $table->boolean('is_unread')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_notifications');
    }
};
