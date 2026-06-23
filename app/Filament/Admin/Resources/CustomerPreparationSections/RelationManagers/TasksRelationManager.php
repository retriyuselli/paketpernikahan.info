<?php

namespace App\Filament\Admin\Resources\CustomerPreparationSections\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Daftar Tugas';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $total = $ownerRecord->tasks()->count();
        $done  = $ownerRecord->tasks()->where('status', 'done')->count();

        return $total > 0 ? "{$done}/{$total}" : null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')
                    ->label('Nama Tugas')
                    ->required()
                    ->maxLength(150)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'todo'    => 'Belum Dikerjakan',
                        'pending' => 'Sedang Diproses',
                        'done'    => 'Selesai',
                    ])
                    ->required()
                    ->default('todo'),

                DatePicker::make('due_date')
                    ->label('Tenggat Waktu')
                    ->nullable(),

                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tugas')
                    ->searchable()
                    ->sortable(),

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
                        'pending' => 'Sedang Diproses',
                        'todo'    => 'Belum Dikerjakan',
                        default   => $state,
                    }),

                TextColumn::make('due_date')
                    ->label('Tenggat')
                    ->date('d M Y')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
