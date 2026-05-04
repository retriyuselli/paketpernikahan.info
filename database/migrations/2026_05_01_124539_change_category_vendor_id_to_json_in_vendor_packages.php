<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->dropForeign(['category_vendor_id']);
        });

        $indexes = DB::select('show index from `vendor_packages`');
        $indexNames = [];
        foreach ($indexes as $index) {
            $column = $index->Column_name ?? null;
            $name = $index->Key_name ?? null;
            if ($column !== 'category_vendor_id') {
                continue;
            }
            if (!$name || $name === 'PRIMARY') {
                continue;
            }
            $indexNames[$name] = true;
        }

        foreach (array_keys($indexNames) as $indexName) {
            DB::statement("alter table `vendor_packages` drop index `{$indexName}`");
        }

        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->json('category_vendor_id')->nullable()->change();
        });

        DB::statement(
            "update `vendor_packages`
             set `category_vendor_id` = json_array(`category_vendor_id`)
             where `category_vendor_id` is not null
               and json_type(`category_vendor_id`) <> 'ARRAY'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('category_vendor_id_tmp')->nullable();
        });

        DB::statement(
            "update `vendor_packages`
             set `category_vendor_id_tmp` = cast(
                 json_unquote(
                     case
                         when json_type(`category_vendor_id`) = 'ARRAY' then json_extract(`category_vendor_id`, '$[0]')
                         else json_extract(`category_vendor_id`, '$')
                     end
                 ) as unsigned
             )
             where `category_vendor_id` is not null"
        );

        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->dropColumn('category_vendor_id');
        });

        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->renameColumn('category_vendor_id_tmp', 'category_vendor_id');
        });

        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->foreign('category_vendor_id')->references('id')->on('category_vendors')->nullOnDelete();
        });
    }
};
