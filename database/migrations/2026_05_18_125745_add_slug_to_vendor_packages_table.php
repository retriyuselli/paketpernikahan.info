<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Populate slugs for existing packages
        $packages = DB::table('vendor_packages')
            ->join('vendors', 'vendor_packages.vendor_id', '=', 'vendors.id')
            ->select('vendor_packages.id', 'vendor_packages.name', 'vendors.slug as vendor_slug')
            ->get();

        $usedSlugs = [];

        foreach ($packages as $pkg) {
            $base = ($pkg->vendor_slug ?: 'vendor') . '-' . Str::slug($pkg->name ?: 'paket');
            $slug = $base;
            $i = 2;
            while (in_array($slug, $usedSlugs)) {
                $slug = $base . '-' . $i++;
            }
            $usedSlugs[] = $slug;

            DB::table('vendor_packages')
                ->where('id', $pkg->id)
                ->update(['slug' => $slug]);
        }

        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
