<?php

namespace App\Filament\Admin\Resources\Vendors\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $title = 'Booking';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->bookings()->count();

        return $count > 0 ? (string) $count : null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_package_id')
                            ->label('Paket')
                            ->options(fn () => $this->ownerRecord->packages()
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn ($p) => [$p->id => ($p->name . ' — ' . $p->price)])
                                ->toArray())
                            ->searchable()
                            ->nullable()
                            ->columnSpanFull(),
                        DatePicker::make('event_date')
                            ->label('Tanggal Acara')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Nomor WhatsApp')
                            ->required()
                            ->maxLength(30),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'contacted' => 'Contacted',
                                'confirmed' => 'Confirmed',
                                'done' => 'Done',
                                'no_response' => 'No Response',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->nullable()
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('vendorPackage.name')
                    ->label('Paket')
                    ->placeholder('Tanpa paket')
                    ->toggleable(),
                TextColumn::make('event_date')
                    ->label('Tanggal Acara')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        'no_response' => 'gray',
                        'contacted' => 'info',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
