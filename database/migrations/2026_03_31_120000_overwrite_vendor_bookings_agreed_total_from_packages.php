<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('vendor_bookings')
            ->whereNotNull('vendor_package_id')
            ->update([
                'agreed_total' => DB::raw('(select price_raw from vendor_packages where vendor_packages.id = vendor_bookings.vendor_package_id)'),
            ]);
    }

    public function down(): void
    {
    }
};

