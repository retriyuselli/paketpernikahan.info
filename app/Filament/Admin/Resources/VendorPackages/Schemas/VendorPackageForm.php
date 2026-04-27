<?php

namespace App\Filament\Admin\Resources\VendorPackages\Schemas;

use App\Models\CategoryVendor;
use App\Models\Vendor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\RawJs;
use Filament\Schemas\Schema;

class VendorPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Paket')
                            ->columns(2)
                            ->schema([
                                Select::make('vendor_id')
                                    ->label('Vendor')
                                    ->options(Vendor::pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->required(),
                                Select::make('category_vendor_id')
                                    ->label('Kategori Vendor')
                                    ->options(CategoryVendor::where('is_active', true)->orderBy('sort_order')->orderBy('name')->pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->placeholder('Ikuti kategori vendor'),
                                FileUpload::make('image_path')
                                    ->label('Foto Paket')
                                    ->image()
                                    ->disk('public')
                                    ->directory('package-galleries')
                                    ->multiple()
                                    ->reorderable()
                                    ->columnSpanFull(),
                                TextInput::make('name')
                                    ->label('Nama Paket')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('price')
                                    ->label('Harga (tampil)')
                                    ->required()
                                    ->placeholder('Rp 45.000.000')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('price_raw', (int) preg_replace('/[^\d]/', '', (string) $state));
                                    }),
                                TextInput::make('price_raw')
                                    ->hidden()
                                    ->dehydrated()
                                    ->numeric()
                                    ->default(0),
                                TextInput::make('discount')
                                    ->label('Potongan Harga')
                                    ->prefix('Rp. ')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', (string) $state))
                                    ->placeholder('0')
                                    ->helperText('Isi jika ada potongan harga khusus'),
                                TextInput::make('dp_paket')
                                    ->label('Down Payment (DP)')
                                    ->prefix('Rp. ')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', (string) $state))
                                    ->placeholder('0')
                                    ->default(0),
                                TextInput::make('max_guests')
                                    ->label('Kapasitas Tamu')
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Fasilitas Termasuk')
                            ->schema([
                                RichEditor::make('item')
                                    ->label('Item Fasilitas')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'bulletList',
                                        'orderedList',
                                        'h2',
                                        'h3',
                                    ])
                                    ->columnSpanFull()
                                    ->required(),
                            ]),

                        Tab::make('Detail Venue & Kapasitas')
                            ->columns(2)
                            ->visible(function (Get $get): bool {
                                $allowed = ['rumah', 'hotel', 'venue', 'gedung'];

                                $catId = $get('category_vendor_id');
                                if (filled($catId)) {
                                    $slug = CategoryVendor::whereKey($catId)->value('slug');
                                    if (in_array((string) $slug, $allowed, true)) {
                                        return true;
                                    }
                                }

                                $vendorId = $get('vendor_id');
                                if (!filled($vendorId)) {
                                    return false;
                                }

                                $vendorCategory = Vendor::whereKey($vendorId)->value('category');
                                return in_array((string) $vendorCategory, $allowed, true);
                            })
                            ->schema([
                                Select::make('type')
                                    ->label('Tipe Vendor/Venue')
                                    ->options([
                                        'Indoor' => 'Indoor',
                                        'Outdoor' => 'Outdoor',
                                        'Semi Outdoor' => 'Semi Outdoor',
                                        'Lainnya' => 'Lainnya',
                                    ]),
                                TextInput::make('capacity')
                                    ->label('Kapasitas (Orang)')
                                    ->numeric()
                                    ->minValue(0),
                                Select::make('facilities')
                                    ->label('Fasilitas Venue')
                                    ->multiple()
                                    ->options([
                                        'Parkir Luas' => 'Parkir Luas',
                                        'Ruang Rias' => 'Ruang Rias',
                                        'Toilet' => 'Toilet',
                                        'Mushola' => 'Mushola',
                                        'Sound System' => 'Sound System',
                                        'AC' => 'AC',
                                        'WiFi' => 'WiFi',
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Tampilan Kartu')
                            ->columns(2)
                            ->schema([
                                ColorPicker::make('card_color')
                                    ->label('Warna Kartu')
                                    ->required()
                                    ->default('#C8D5B9'),
                                ColorPicker::make('card_text_color')
                                    ->label('Warna Teks')
                                    ->required()
                                    ->default('#444444'),
                            ]),

                        Tab::make('Galeri Paket')
                            ->columns(2)
                            ->schema([
                                Repeater::make('galleries')
                                    ->label('Video Galeri')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('caption')
                                            ->label('Keterangan')
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        FileUpload::make('image_video')
                                            ->label('Image Video')
                                            ->disk('public')
                                            ->directory('package-galleries')
                                            ->columnSpanFull(),
                                        TextInput::make('video_url')
                                            ->label('Link Video YouTube')
                                            ->url()
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->columnSpanFull(),
                                    ])
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string =>
                                        isset($state['caption']) && $state['caption'] ? $state['caption'] : 'Video'
                                    )
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
