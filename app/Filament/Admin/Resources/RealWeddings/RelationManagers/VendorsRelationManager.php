<?php

namespace App\Filament\Admin\Resources\RealWeddings\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VendorsRelationManager extends RelationManager
{
    protected static string $relationship = 'vendors';

    protected static ?string $title = 'Vendors Involved';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('category')
                    ->label('Kategori')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('WEDDING PLANNER'),
                TextInput::make('name')
                    ->label('Nama Vendor')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('VARAWEDDING'),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                FileUpload::make('image')
                    ->label('Foto Vendor')
                    ->image()
                    ->disk('public')
                    ->directory('real-wedding-vendors')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('public')
                    ->height(50),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('name')
                    ->label('Nama Vendor')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
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
