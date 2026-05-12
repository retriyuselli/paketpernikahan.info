<?php

namespace App\Filament\Admin\Resources\RealWeddings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;

class RealWeddingForm
{
    public static function schema(): array
    {
        return [
            Section::make('Artikel')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul Artikel')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    TextInput::make('couple_names')
                        ->label('Nama Pasangan')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('MARCEL & STELCYA'),
                    TextInput::make('venue_name')
                        ->label('Nama Venue')
                        ->maxLength(255)
                        ->placeholder('Atma Sahita'),
                    DatePicker::make('wedding_date')
                        ->label('Tanggal Pernikahan'),
                    DateTimePicker::make('published_at')
                        ->label('Tanggal Publish'),
                    TextInput::make('badge')
                        ->label('Badge')
                        ->default("Editor's Collection")
                        ->maxLength(255),
                    Select::make('vendors')
                        ->label('Vendor Terlibat')
                        ->relationship(
                            name: 'vendors',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->where('is_active', true),
                        )
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                    Select::make('vendorPackages')
                        ->label('Paket Vendor')
                        ->relationship(
                            name: 'vendorPackages',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->where('is_active', true),
                        )
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Konten')
                ->schema([
                    RichEditor::make('content')
                        ->label('Isi Artikel')
                        ->columnSpanFull(),
                ]),

            Section::make('Cover & Status')
                ->schema([
                    FileUpload::make('cover_image')
                        ->label('Foto Cover')
                        ->image()
                        ->disk('public')
                        ->directory('real-weddings')
                        ->columnSpanFull(),
                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
        ];
    }
}
