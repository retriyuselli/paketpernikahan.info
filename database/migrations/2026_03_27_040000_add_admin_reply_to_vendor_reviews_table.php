<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_reviews', function (Blueprint $table) {
            $table->text('admin_reply')->nullable()->after('body');
            $table->foreignId('admin_reply_by')->nullable()->constrained('users')->nullOnDelete()->after('admin_reply');
            $table->timestamp('admin_replied_at')->nullable()->after('admin_reply_by');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_reply_by');
            $table->dropColumn(['admin_reply', 'admin_replied_at']);
        });
    }
};

