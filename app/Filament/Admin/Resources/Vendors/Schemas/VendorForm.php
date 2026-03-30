<?php

namespace App\Filament\Admin\Resources\Vendors\Schemas;

use App\Enums\VendorBadge;
use App\Enums\VendorPromo;
use App\Enums\ProvinsiEnum;
use App\Models\CategoryVendor;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Informasi Utama')
                            ->columns(2)
                            ->schema([
                                Select::make('owner_user_id')
                                    ->label('Pemilik Vendor')
                                    ->options(fn () => User::query()
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->toArray())
                                    ->searchable()
                                    ->nullable()
                                    ->columnSpanFull(),
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) =>
                                        $set('slug', Str::slug($state))
                                    ),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->hidden()
                                    ->dehydrated(),
                                Select::make('category')
                                    ->required()
                                    ->options(fn () => CategoryVendor::orderBy('sort_order')
                                        ->pluck('name', 'slug')
                                        ->toArray()
                                    )
                                    ->searchable()
                                    ->live(),
                                Select::make('type')
                                    ->required(fn ($get) => in_array($get('category'), ['gedung', 'hotel', 'venue', 'rumah']))
                                    ->visible(fn ($get) => in_array($get('category'), ['gedung', 'hotel', 'venue', 'rumah']))
                                    ->options([
                                        'Indoor'           => 'Indoor',
                                        'Outdoor'          => 'Outdoor',
                                        'Indoor & Outdoor' => 'Indoor & Outdoor',
                                    ])
                                    ->searchable(),
                                TextInput::make('location')
                                    ->required()
                                    ->label('Alamat')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Select::make('province')
                                    ->label('Provinsi')
                                    ->required()
                                    ->options(ProvinsiEnum::toArray())
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn (callable $set) => $set('city', null)),
                                Select::make('city')
                                    ->label('Kota / Kabupaten')
                                    ->required()
                                    ->searchable()
                                    ->options(fn ($get) => collect(ProvinsiEnum::getKotaKabupaten($get('province') ?? ''))
                                        ->mapWithKeys(fn ($v) => [$v => $v])
                                        ->toArray()
                                    )
                                    ->disabled(fn ($get) => blank($get('province')))
                                    ->placeholder(fn ($get) => blank($get('province')) ? 'Pilih provinsi terlebih dahulu' : 'Pilih kota'),
                                Textarea::make('description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Kontak')
                            ->columns(3)
                            ->schema([
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(30),
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('instagram')
                                    ->maxLength(100),
                            ]),

                        Tab::make('Detail Venue')
                            ->columns(3)
                            ->schema([
                                TextInput::make('capacity')->maxLength(100),
                                TextInput::make('venue_type')->maxLength(100),
                                TextInput::make('experience')->maxLength(50),
                                TextInput::make('facilities')->columnSpanFull(),
                            ]),

                        Tab::make('Harga & Statistik')
                            ->columns(3)
                            ->schema([
                                TextInput::make('price_start')
                                    ->prefix('Rp. ')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', (string) $state))
                                    ->placeholder('0'),
                                TextInput::make('rating')
                                    ->numeric()->minValue(0)->maxValue(5)->step(0.1),
                                TextInput::make('events_done')
                                    ->numeric()->default(0),
                                TextInput::make('likes')
                                    ->numeric()->default(0),
                                TextInput::make('comments_count')
                                    ->numeric()->default(0),
                            ]),

                        Tab::make('Label')
                            ->columns(2)
                            ->schema([
                                Select::make('badge')
                                    ->options(VendorBadge::options())
                                    ->multiple()
                                    ->placeholder('— Tanpa Badge —')
                                    ->nullable(),
                                Select::make('promo')
                                    ->options(VendorPromo::options())
                                    ->multiple()
                                    ->placeholder('— Tanpa Promo —')
                                    ->nullable(),
                                Toggle::make('is_active')
                                    ->default(true),
                            ]),

                        Tab::make('Galeri Foto')
                            ->columns(2)
                            ->schema([
                                FileUpload::make('logo_vendor')
                                    ->label('Logo Vendor')
                                    ->image()
                                    ->disk('public')
                                    ->directory('vendor_logos')
                                    ->columnSpanFull(),
                                FileUpload::make('cover_image')
                                    ->label('Foto Cover')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->disk('public')
                                    ->directory('galleries')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Video YouTube')
                            ->schema([
                                Repeater::make('videoGalleries')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('video_url')
                                            ->label('URL Video (YouTube)')
                                            ->url()
                                            ->required()
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->columnSpanFull(),
                                        FileUpload::make('cover_video')
                                            ->label('Foto Cover Video')
                                            ->image()
                                            ->disk('public')
                                            ->directory('galleries')
                                            ->columnSpanFull(),
                                        TextInput::make('caption')
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                    ])
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string =>
                                        isset($state['caption']) && $state['caption']
                                            ? $state['caption']
                                            : 'Video'
                                    )
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
