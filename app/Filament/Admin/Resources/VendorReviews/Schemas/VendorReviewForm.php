<?php

namespace App\Filament\Admin\Resources\VendorReviews\Schemas;

use App\Models\Vendor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VendorReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ulasan')
                    ->columns(2)
                    ->schema([
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->options(Vendor::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('rating')
                            ->label('Rating')
                            ->options([1=>'⭐ 1',2=>'⭐⭐ 2',3=>'⭐⭐⭐ 3',4=>'⭐⭐⭐⭐ 4',5=>'⭐⭐⭐⭐⭐ 5'])
                            ->required(),
                        TextInput::make('reviewer_name')
                            ->label('Nama Reviewer')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('reviewer_avatar')
                            ->label('URL Avatar')
                            ->url()
                            ->maxLength(255),
                        DatePicker::make('reviewed_at')
                            ->label('Tanggal Review')
                            ->required()
                            ->default(now()),
                        Toggle::make('is_approved')
                            ->label('Disetujui')
                            ->default(false),
                        Textarea::make('body')
                            ->label('Isi Ulasan')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
