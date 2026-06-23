<?php

namespace App\Filament\Admin\Resources\WeddingInfos\RelationManagers;

use App\Models\WeddingEvent;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class WeddingEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'Rangkaian Acara';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->events()->count();
        return $count > 0 ? (string) $count : null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                Select::make('jenis_acara')
                    ->label('Jenis Acara')
                    ->options(WeddingEvent::$jenisOptions)
                    ->required(),

                DatePicker::make('tgl_acara')
                    ->label('Tanggal Acara')
                    ->nullable(),

                TextInput::make('lokasi_acara')
                    ->label('Lokasi Acara')
                    ->placeholder('contoh: Masjid Al-Ikhlas, Jl. Sudirman No. 10')
                    ->maxLength(200)
                    ->nullable()
                    ->columnSpanFull(),

                Select::make('vendor_booking_id')
                    ->label('Paket / Booking')
                    ->relationship(
                        name: 'vendorBooking',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('user_id', $this->ownerRecord->user_id)
                            ->with(['vendor', 'vendorPackage']),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) =>
                        ($record->vendor?->name ?? 'Vendor') .
                        ($record->vendorPackage ? ' — ' . $record->vendorPackage->name : '') .
                        ' (' . $record->event_date?->format('d M Y') . ')'
                    )
                    ->searchable()
                    ->nullable()
                    ->helperText('Opsional — hubungkan ke booking vendor yang sudah ada.')
                    ->columnSpanFull(),

                Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(2)
                    ->nullable()
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public function table(Table $table): Table
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
                    ->formatStateUsing(fn ($state) => WeddingEvent::$jenisOptions[$state] ?? $state),

                TextColumn::make('tgl_acara')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('lokasi_acara')
                    ->label('Lokasi')
                    ->limit(50)
                    ->placeholder('—'),

                TextColumn::make('vendorBooking.vendor.name')
                    ->label('Vendor / Paket')
                    ->placeholder('—')
                    ->formatStateUsing(function ($state, Model $record) {
                        if (! $record->vendorBooking) return '—';
                        $vendor  = $record->vendorBooking->vendor?->name ?? '—';
                        $package = $record->vendorBooking->vendorPackage?->name;
                        return $package ? "{$vendor} — {$package}" : $vendor;
                    }),
            ])
            ->defaultSort('tgl_acara')
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = $this->ownerRecord->user_id;
                        return $data;
                    }),
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
}
