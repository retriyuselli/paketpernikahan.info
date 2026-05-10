<?php

namespace App\Filament\Admin\Resources\HomeAds\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class HomeAdForm
{
    public static function schema(): array
    {
        return [
            Section::make('Jenis Iklan')
                ->schema([
                    Select::make('type')
                        ->label('Jenis Iklan')
                        ->options([
                            'card'   => 'Highlight Card (halaman Home)',
                            'banner' => 'Banner Iklan (di bawah breadcrumb)',
                        ])
                        ->default('card')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Konten Iklan')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul / Nama Sponsor')
                        ->maxLength(255),

                    FileUpload::make('image')
                        ->label('Gambar Iklan')
                        ->image()
                        ->disk('public')
                        ->directory('home-ads')
                        ->required()
                        ->helperText('Card: 800×800px (square) untuk highlight & popup | Banner: 1456×180px (2x leaderboard, WebP/JPG)')
                        ->columnSpanFull(),

                    Textarea::make('caption')
                        ->label('Caption')
                        ->rows(2)
                        ->maxLength(300)
                        ->helperText('Card: teks kecil di bawah judul | Banner: tagline/sub-judul sponsor')
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
