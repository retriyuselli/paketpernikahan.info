<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reviewer_name');                // guest name or user name
            $table->string('reviewer_avatar')->nullable();
            $table->unsignedTinyInteger('rating');          // 1-5
            $table->text('body');
            $table->text('admin_reply')->nullable();
            $table->foreignId('admin_reply_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('admin_replied_at')->nullable();
            $table->date('reviewed_at');
            $table->boolean('is_approved')->default(true);
            $table->string('reviewer_ip', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_reviews');
    }
};
