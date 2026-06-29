<?php

namespace App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates;

use App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates\Pages\CreateWeddingPaymentScheduleTemplate;
use App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates\Pages\EditWeddingPaymentScheduleTemplate;
use App\Filament\Admin\Resources\WeddingPaymentScheduleTemplates\Pages\ListWeddingPaymentScheduleTemplates;
use App\Models\WeddingPaymentScheduleTemplate;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class WeddingPaymentScheduleTemplateResource extends Resource
{
    protected static ?string $model = WeddingPaymentScheduleTemplate::class;

    protected static ?string $modelLabel = 'Template Jadwal Pembayaran';
    protected static ?string $pluralModelLabel = 'Template Jadwal Pembayaran';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';
    protected static string|UnitEnum|null $navigationGroup = 'Customer';
    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('jenis_acara')
                ->label('Jenis Acara')
                ->options(WeddingPaymentScheduleTemplate::$jenisOptions)
                ->required(),

            TextInput::make('title')
                ->label('Judul Tagihan')
                ->required()
                ->maxLength(200),

            TextInput::make('vendor_name')
                ->label('Vendor')
                ->required()
                ->maxLength(200),

            Select::make('category')
                ->label('Kategori')
                ->options([
                    'venue'         => 'Venue',
                    'catering'      => 'Catering',
                    'decoration'    => 'Dekorasi',
                    'photo_video'   => 'Foto & Video',
                    'entertainment' => 'Entertainment',
                    'makeup'        => 'Makeup & Busana',
                    'transport'     => 'Transportasi',
                    'wo'            => 'Wedding Organizer',
                    'other'         => 'Lainnya',
                ])
                ->default('other')
                ->required(),

            TextInput::make('amount')
                ->label('Nominal Default')
                ->prefix('Rp')
                ->numeric()
                ->minValue(0)
                ->required(),

            TextInput::make('due_days_before_event')
                ->label('Jatuh Tempo H-')
                ->numeric()
                ->minValue(0)
                ->required(),

            TextInput::make('sort_order')
                ->label('Urutan')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),

            Textarea::make('notes')
                ->label('Catatan')
                ->rows(3)
                ->nullable()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_acara')
                    ->label('Acara')
                    ->badge()
                    ->formatStateUsing(fn ($state) => WeddingPaymentScheduleTemplate::$jenisOptions[$state] ?? $state)
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('vendor_name')
                    ->label('Vendor')
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('due_days_before_event')
                    ->label('H-')
                    ->suffix(' hari')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->defaultSort('jenis_acara')
            ->filters([
                SelectFilter::make('jenis_acara')
                    ->label('Jenis Acara')
                    ->options(WeddingPaymentScheduleTemplate::$jenisOptions),

                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Nonaktif',
                    ]),
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
            'index'  => ListWeddingPaymentScheduleTemplates::route('/'),
            'create' => CreateWeddingPaymentScheduleTemplate::route('/create'),
            'edit'   => EditWeddingPaymentScheduleTemplate::route('/{record}/edit'),
        ];
    }
}
