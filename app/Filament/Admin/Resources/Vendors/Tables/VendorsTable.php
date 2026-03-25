<?php

namespace App\Filament\Admin\Resources\Vendors\Tables;

use App\Enums\ProvinsiEnum;
use App\Models\CategoryVendor;
use App\Models\Vendor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select as FormSelect;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://picsum.photos/seed/' . $record->slug . '-hero/80/80'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string =>
                        CategoryVendor::where('slug', $state)->value('name') ?? $state
                    )
                    ->color(fn (string $state): string =>
                        CategoryVendor::where('slug', $state)->value('color') ?? 'gray'
                    ),
                TextColumn::make('location')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('price_start')
                    ->sortable()
                    ->numeric()
                    ->prefix('Rp. '),
                TextColumn::make('rating')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('promo')
                    ->badge()
                    ->color('success')
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(fn () => CategoryVendor::orderBy('sort_order')
                        ->pluck('name', 'slug')
                        ->toArray()
                    )
                    ->searchable(),
                Filter::make('lokasi')
                    ->label('Lokasi')
                    ->schema([
                        FormSelect::make('province')
                            ->label('Provinsi')
                            ->options(fn () => Vendor::whereNotNull('province')
                                ->distinct()->orderBy('province')->pluck('province', 'province')->toArray()
                            )
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('city', null))
                            ->placeholder('Semua Provinsi'),
                        FormSelect::make('city')
                            ->label('Kota')
                            ->options(fn (Get $get): array =>
                                blank($get('province'))
                                    ? []
                                    : Vendor::where('province', $get('province'))
                                        ->whereNotNull('city')->distinct()->orderBy('city')
                                        ->pluck('city', 'city')->toArray()
                            )
                            ->searchable()
                            ->disabled(fn (Get $get): bool => blank($get('province')))
                            ->placeholder(fn (Get $get): string => blank($get('province')) ? 'Pilih provinsi dulu' : 'Semua Kota'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $query
                            ->when($data['province'] ?? null, fn ($q, $v) => $q->where('province', $v))
                            ->when($data['city'] ?? null, fn ($q, $v) => $q->where('city', $v))
                    )
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (filled($data['province'] ?? null)) {
                            $indicators[] = Indicator::make('Provinsi: ' . $data['province'])->removeField('province');
                        }
                        if (filled($data['city'] ?? null)) {
                            $indicators[] = Indicator::make('Kota: ' . $data['city'])->removeField('city');
                        }
                        return $indicators;
                    }),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([1 => 'Aktif', 0 => 'Non-Aktif']),
            ])
            ->defaultSort('rating', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
