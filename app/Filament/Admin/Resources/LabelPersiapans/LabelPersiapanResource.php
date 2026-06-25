<?php

namespace App\Filament\Admin\Resources\LabelPersiapans;

use App\Filament\Admin\Resources\LabelPersiapans\Pages\CreateLabelPersiapan;
use App\Filament\Admin\Resources\LabelPersiapans\Pages\EditLabelPersiapan;
use App\Filament\Admin\Resources\LabelPersiapans\Pages\ListLabelPersiapans;
use App\Models\LabelPersiapan;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class LabelPersiapanResource extends Resource
{
    protected static ?string $model = LabelPersiapan::class;

    protected static ?string $modelLabel       = 'Label Persiapan';
    protected static ?string $pluralModelLabel = 'Label Persiapan';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static string|UnitEnum|null $navigationGroup  = 'Customer';
    protected static ?int $navigationSort                    = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('jenis_acara')
                ->label('Jenis Acara')
                ->options(LabelPersiapan::$jenisOptions)
                ->required(),

            TextInput::make('nama')
                ->label('Nama Label')
                ->placeholder('contoh: Venue, Dokumen Nikah')
                ->required()
                ->maxLength(100),

            TextInput::make('sort_order')
                ->label('Urutan')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_acara')
                    ->label('Jenis Acara')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'lamaran'   => 'info',
                        'pengajian' => 'warning',
                        'akad'      => 'success',
                        'resepsi'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => LabelPersiapan::$jenisOptions[$state] ?? $state)
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama Label')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('jenis_acara')
            ->filters([
                SelectFilter::make('jenis_acara')
                    ->label('Jenis Acara')
                    ->options(LabelPersiapan::$jenisOptions),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLabelPersiapans::route('/'),
            'create' => CreateLabelPersiapan::route('/create'),
            'edit'   => EditLabelPersiapan::route('/{record}/edit'),
        ];
    }
}
