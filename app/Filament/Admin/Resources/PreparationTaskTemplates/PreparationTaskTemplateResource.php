<?php

namespace App\Filament\Admin\Resources\PreparationTaskTemplates;

use App\Filament\Admin\Resources\PreparationTaskTemplates\Pages\CreatePreparationTaskTemplate;
use App\Filament\Admin\Resources\PreparationTaskTemplates\Pages\EditPreparationTaskTemplate;
use App\Filament\Admin\Resources\PreparationTaskTemplates\Pages\ListPreparationTaskTemplates;
use App\Models\LabelPersiapan;
use App\Models\PreparationTaskTemplate;
use BackedEnum;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PreparationTaskTemplateResource extends Resource
{
    protected static ?string $model = PreparationTaskTemplate::class;

    protected static ?string $modelLabel        = 'Template Tugas';
    protected static ?string $pluralModelLabel  = 'Template Tugas Persiapan';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static string|UnitEnum|null $navigationGroup   = 'Customer';
    protected static ?int $navigationSort                     = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('jenis_acara')
                ->label('Jenis Acara')
                ->options(PreparationTaskTemplate::$jenisOptions)
                ->required()
                ->live()
                ->afterStateUpdated(fn ($set) => $set('label', null)),

            Select::make('label')
                ->label('Kategori')
                ->options(fn (Get $get) => LabelPersiapan::optionsFor($get('jenis_acara') ?? ''))
                ->required()
                ->searchable()
                ->placeholder('Pilih kategori…'),

            TextInput::make('title')
                ->label('Nama Tugas')
                ->placeholder('contoh: Survey dan pilih venue')
                ->required()
                ->maxLength(200)
                ->columnSpanFull(),

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
                    ->label('Acara')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'lamaran'   => 'info',
                        'pengajian' => 'warning',
                        'akad'      => 'success',
                        'resepsi'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => PreparationTaskTemplate::$jenisOptions[$state] ?? $state)
                    ->sortable(),

                TextColumn::make('label')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Nama Tugas')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('jenis_acara')
            ->filters([
                SelectFilter::make('jenis_acara')
                    ->label('Jenis Acara')
                    ->options(PreparationTaskTemplate::$jenisOptions),

                SelectFilter::make('label')
                    ->label('Kategori')
                    ->options(
                        PreparationTaskTemplate::query()
                            ->distinct()
                            ->orderBy('label')
                            ->pluck('label', 'label')
                    ),
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
            'index'  => ListPreparationTaskTemplates::route('/'),
            'create' => CreatePreparationTaskTemplate::route('/create'),
            'edit'   => EditPreparationTaskTemplate::route('/{record}/edit'),
        ];
    }
}
