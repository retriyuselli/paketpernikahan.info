<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['family_members', 'vip_guests'] as $table) {
            if (! Schema::hasColumn($table, 'no')) {
                continue;
            }

            $nextNumbers = DB::table($table)
                ->select('user_id', DB::raw('COALESCE(MAX(no), 0) + 1 as next_no'))
                ->groupBy('user_id')
                ->pluck('next_no', 'user_id');

            DB::table($table)
                ->whereNull('no')
                ->orderBy('user_id')
                ->orderBy('id')
                ->get(['id', 'user_id'])
                ->each(function ($record) use ($table, $nextNumbers): void {
                    $nextNo = (int) ($nextNumbers[$record->user_id] ?? 1);

                    DB::table($table)
                        ->where('id', $record->id)
                        ->update(['no' => $nextNo]);

                    $nextNumbers[$record->user_id] = $nextNo + 1;
                });
        }
    }

    public function down(): void
    {
        //
    }
};
