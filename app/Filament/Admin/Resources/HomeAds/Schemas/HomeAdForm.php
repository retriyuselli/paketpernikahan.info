<?php

namespace App\Filament\Admin\Resources\HomeAds\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class HomeAdForm
{
    public static function schema(): array
    {
        return [
            Section::make('Konten Iklan')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul Iklan')
                        ->maxLength(255),

                    FileUpload::make('image')
                        ->label('Gambar Iklan')
                        ->image()
                        ->disk('public')
                        ->directory('home-ads')
                        ->required()
                        ->helperText('Rekomendasi: rasio 1:1 (persegi), min. 800×800px')
                        ->columnSpanFull(),

                    Textarea::make('caption')
                        ->label('Caption')
                        ->rows(2)
                        ->maxLength(300)
                        ->helperText('Teks kecil yang tampil di bawah gambar modal')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('CTA & Pengaturan')
                ->schema([
                    TextInput::make('link_url')
                        ->label('URL Tujuan (klik modal)')
                        ->url()
                        ->maxLength(500)
                        ->placeholder('https://...'),

                    TextInput::make('link_label')
                        ->label('Label Tombol CTA')
                        ->maxLength(100)
                        ->placeholder('Lihat Promo'),

                    TextInput::make('delay_seconds')
                        ->label('Delay Tampil (detik)')
                        ->numeric()
                        ->default(5)
                        ->minValue(0)
                        ->maxValue(60),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true)
                        ->columnSpanFull(),
                ])->columns(2),
        ];
    }
}
