<?php

namespace App\Filament\Imports;

use App\Models\CategoryVendor;
use App\Models\Vendor;
use App\Models\VendorPackage;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class VendorPackageImporter extends Importer
{
    protected static ?string $model = VendorPackage::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('vendor_id')
                ->label('Nama Vendor')
                ->requiredMapping()
                ->fillRecordUsing(function (VendorPackage $record, string $state): void {
                    $name = trim($state);
                    $vendor = Vendor::firstOrCreate(
                        ['name' => $name],
                        [
                            'slug'     => static::uniqueSlug($name),
                            'category' => 'other',
                            'location' => '-',
                        ]
                    );
                    $record->vendor_id = $vendor->id;
                }),

            ImportColumn::make('category_vendor_id')
                ->label('Kategori')
                ->fillRecordUsing(function (VendorPackage $record, string $state): void {
                    $name = trim($state);
                    if ($name === '') {
                        return;
                    }
                    $category = CategoryVendor::firstOrCreate(
                        ['name' => $name],
                        ['slug' => Str::slug($name) ?: Str::uuid()->toString()]
                    );
                    $record->category_vendor_id = [$category->id];
                }),

            ImportColumn::make('name')
                ->label('Nama Paket')
                ->requiredMapping(),

            ImportColumn::make('item')
                ->label('Item / Fasilitas'),

            ImportColumn::make('max_guests')
                ->label('Maks. Tamu'),

            ImportColumn::make('price')
                ->label('Harga')
                ->fillRecordUsing(function (VendorPackage $record, string $state): void {
                    $record->price = (int) preg_replace('/[^\d]/', '', $state);
                }),

            ImportColumn::make('dp_paket')
                ->label('DP Paket')
                ->fillRecordUsing(function (VendorPackage $record, string $state): void {
                    $record->dp_paket = (int) preg_replace('/[^\d]/', '', $state);
                }),
        ];
    }

    private static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: Str::uuid()->toString();
        $slug = $base;
        $i    = 1;
        while (Vendor::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function resolveRecord(): VendorPackage
    {
        return new VendorPackage();
    }

    protected function beforeCreate(): void
    {
        if (empty($this->record->max_guests)) {
            $this->record->max_guests = '-';
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import paket vendor selesai. '
            . Number::format($import->successful_rows) . ' '
            . str('baris')->plural($import->successful_rows) . ' berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' '
                . str('baris')->plural($failedRowsCount) . ' gagal diimport.';
        }

        return $body;
    }
}
