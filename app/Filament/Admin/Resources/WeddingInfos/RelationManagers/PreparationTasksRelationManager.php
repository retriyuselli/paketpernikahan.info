<?php

namespace App\Filament\Admin\Resources\WeddingInfos\RelationManagers;

use App\Models\CustomerPreparationTask;
use App\Models\LabelPersiapan;
use App\Models\WeddingEvent;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PreparationTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'preparationTasks';

    protected static ?string $title = 'Persiapan per Acara';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $total = $ownerRecord->preparationTasks()->count();
        $done  = $ownerRecord->preparationTasks()->where('status', 'done')->count();
        return $total > 0 ? "{$done}/{$total}" : null;
    }

    public function form(Schema $schema): Schema
    {
        $userId = $this->ownerRecord->user_id;

        return $schema->components([
            Select::make('wedding_event_id')
                ->label('Acara')
                ->options(fn () => WeddingEvent::where('user_id', $userId)
                    ->orderBy('tgl_acara')
                    ->get()
                    ->mapWithKeys(fn ($e) => [
                        $e->id => (WeddingEvent::$jenisOptions[$e->jenis_acara] ?? $e->jenis_acara)
                            . ($e->tgl_acara ? ' — ' . $e->tgl_acara->format('d M Y') : ''),
                    ]))
                ->required()
                ->live(),

            Select::make('label')
                ->label('Kategori')
                ->options(function (Get $get) use ($userId) {
                    $eventId = $get('wedding_event_id');
                    if (! $eventId) return [];

                    $event = WeddingEvent::find($eventId);
                    if (! $event) return [];

                    return LabelPersiapan::optionsFor($event->jenis_acara);
                })
                ->nullable()
                ->searchable()
                ->placeholder('Pilih kategori…'),

            TextInput::make('title')
                ->label('Nama Tugas')
                ->required()
                ->maxLength(200)
                ->columnSpanFull(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'todo'    => 'Belum Dikerjakan',
                    'pending' => 'Sedang Dikerjakan',
                    'done'    => 'Selesai',
                ])
                ->default('todo')
                ->required(),

            DatePicker::make('due_date')
                ->label('Deadline')
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('weddingEvent.jenis_acara')
                    ->label('Acara')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'lamaran'   => 'info',
                        'pengajian' => 'warning',
                        'akad'      => 'success',
                        'resepsi'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => WeddingEvent::$jenisOptions[$state] ?? $state)
                    ->sortable(),

                TextColumn::make('label')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Tugas')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'done'    => 'success',
                        'pending' => 'warning',
                        'todo'    => 'gray',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'done'    => 'Selesai',
                        'pending' => 'Sedang Dikerjakan',
                        'todo'    => 'Belum Dikerjakan',
                        default   => $state,
                    }),

                TextColumn::make('due_date')
                    ->label('Deadline')
                    ->date('d M Y')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->defaultSort('wedding_events.tgl_acara')
            ->filters([
                SelectFilter::make('wedding_event_id')
                    ->label('Filter Acara')
                    ->options(fn () => WeddingEvent::where('user_id', $this->ownerRecord->user_id)
                        ->orderBy('tgl_acara')
                        ->get()
                        ->mapWithKeys(fn ($e) => [
                            $e->id => (WeddingEvent::$jenisOptions[$e->jenis_acara] ?? $e->jenis_acara)
                                . ($e->tgl_acara ? ' — ' . $e->tgl_acara->format('d M Y') : ''),
                        ])),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'todo'    => 'Belum Dikerjakan',
                        'pending' => 'Sedang Dikerjakan',
                        'done'    => 'Selesai',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Tugas')
                    ->using(function (array $data): Model {
                        $userId  = $this->ownerRecord->user_id;
                        $maxSort = CustomerPreparationTask::where('wedding_event_id', $data['wedding_event_id'])
                            ->max('sort_order') ?? 0;

                        return CustomerPreparationTask::create([
                            'wedding_event_id' => $data['wedding_event_id'],
                            'section_id'       => null,
                            'label'            => $data['label'] ?? null,
                            'user_id'          => $userId,
                            'title'            => $data['title'],
                            'status'           => $data['status'] ?? 'todo',
                            'due_date'         => $data['due_date'] ?? null,
                            'sort_order'       => $maxSort + 1,
                        ]);
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->using(function (Model $record, array $data): Model {
                        $record->update([
                            'wedding_event_id' => $data['wedding_event_id'],
                            'label'            => $data['label'] ?? $record->label,
                            'title'            => $data['title'],
                            'status'           => $data['status'],
                            'due_date'         => $data['due_date'] ?? null,
                        ]);
                        return $record;
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
