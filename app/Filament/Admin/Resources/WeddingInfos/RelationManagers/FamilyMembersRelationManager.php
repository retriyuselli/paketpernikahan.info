<?php

namespace App\Filament\Admin\Resources\WeddingInfos\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FamilyMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'familyMembers';

    protected static ?string $title = 'Anggota Keluarga';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->familyMembers()->count();
        return $count > 0 ? (string) $count : null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100),

                TextInput::make('role')
                    ->label('Peran')
                    ->placeholder('contoh: Ayah Pengantin Pria')
                    ->maxLength(100)
                    ->nullable(),

                TextInput::make('phone')
                    ->label('No. HP')
                    ->tel()
                    ->maxLength(20)
                    ->nullable(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Peran')
                    ->placeholder('—'),

                TextColumn::make('phone')
                    ->label('No. HP')
                    ->placeholder('—'),
            ])
            ->defaultSort('name')
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
