<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Pages;

use App\Filament\Admin\Resources\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public static function configureTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Oleh')
                    ->default('Sistem')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        default   => $state,
                    }),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn (?string $state) => $state ? class_basename($state) : '-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('perubahan')
                    ->label('Perubahan')
                    ->getStateUsing(function ($record) {
                        $changes = $record->attribute_changes;
                        if (blank($changes)) return '-';

                        $props = $changes instanceof \Illuminate\Support\Collection
                            ? $changes->toArray()
                            : (is_string($changes) ? (json_decode($changes, true) ?? []) : (array) $changes);

                        $old = $props['old'] ?? [];
                        $new = $props['attributes'] ?? [];

                        // konversi nilai apapun (string/array/bool/null) ke string aman untuk HTML
                        $toStr = function ($val): string {
                            if (is_null($val)) return '—';
                            if (is_bool($val)) return $val ? 'true' : 'false';
                            if (is_array($val) || is_object($val)) return e(json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                            return e((string) $val);
                        };

                        if (empty($new) && ! empty($old)) {
                            $lines = [];
                            foreach ($old as $field => $val) {
                                $lines[] = "<span class=\"text-gray-500 text-xs\">{$field}:</span> {$toStr($val)}";
                            }
                            return implode('<br>', $lines);
                        }

                        if (empty($new)) return '-';

                        $lines = [];
                        foreach ($new as $field => $newVal) {
                            $oldVal = $old[$field] ?? null;
                            $lines[] = empty($old)
                                ? "<span class=\"text-gray-500 text-xs\">{$field}:</span> {$toStr($newVal)}"
                                : "<span class=\"text-gray-500 text-xs\">{$field}:</span> {$toStr($oldVal)} → <strong>{$toStr($newVal)}</strong>";
                        }
                        return implode('<br>', $lines);
                    })
                    ->html()
                    ->wrap()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('description')
                    ->label('Aksi')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                    ]),

                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Model')
                    ->options([
                        'App\Models\Blog'                 => 'Blog',
                        'App\Models\CategoryVendor'       => 'Kategori Vendor',
                        'App\Models\ChatMessage'          => 'Chat Message',
                        'App\Models\ChatSession'          => 'Chat Session',
                        'App\Models\HeroCircle'           => 'Hero Circle',
                        'App\Models\HomeAd'               => 'Home Ad',
                        'App\Models\PaketGallery'         => 'Galeri Paket',
                        'App\Models\PartnerLogo'          => 'Partner Logo',
                        'App\Models\RealWedding'          => 'Real Wedding',
                        'App\Models\User'                 => 'User',
                        'App\Models\Vendor'               => 'Vendor',
                        'App\Models\VendorApplication'    => 'Pendaftaran Vendor',
                        'App\Models\VendorBooking'        => 'Booking',
                        'App\Models\VendorBookingPayment' => 'Pembayaran',
                        'App\Models\VendorGallery'        => 'Galeri Vendor',
                        'App\Models\VendorPackage'        => 'Paket',
                        'App\Models\VendorReview'         => 'Ulasan',
                        'App\Models\VenueReviewVideo'     => 'Video Ulasan',
                        'App\Models\Wishlist'             => 'Wishlist',
                    ]),
            ])
            ->actions([])
            ->bulkActions([])
            ->paginated([25, 50, 100]);
    }
}
