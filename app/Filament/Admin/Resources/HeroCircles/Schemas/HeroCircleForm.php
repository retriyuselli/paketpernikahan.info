<?php

namespace App\Filament\Admin\Resources\HeroCircles\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroCircleForm
{
    public static function schema(): array
    {
        return [
            Section::make('Image & Details')
                ->schema([
                    FileUpload::make('image_url')
                        ->label('Image')
                        ->image()
                        ->disk('public')
                        ->directory('hero-circles')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('alt')
                        ->label('Alt Text')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('size_px')
                        ->label('Size (px)')
                        ->required()
                        ->numeric()
                        ->default(64)
                        ->helperText('e.g. 56=w-14, 64=w-16, 72=w-18, 80=w-20'),
                ])->columns(2),

            Section::make('Design & Animation')
                ->schema([
                    ColorPicker::make('color_from')
                        ->label('Gradient Start Color')
                        ->required()
                        ->default('#9CAF88'),
                    ColorPicker::make('color_to')
                        ->label('Gradient End Color')
                        ->required()
                        ->default('#C8D5B9'),
                    TextInput::make('animation_delay')
                        ->label('Animation Delay (s)')
                        ->required()
                        ->numeric()
                        ->default(0.00),
                    TextInput::make('animation_duration')
                        ->label('Animation Duration (s)')
                        ->required()
                        ->numeric()
                        ->default(20.00),
                ])->columns(2),

            Section::make('Positioning')
                ->schema([
                    Select::make('position_side')
                        ->label('Position Side')
                        ->options([
                            'left' => 'Left',
                            'right' => 'Right',
                        ])
                        ->required()
                        ->default('left'),
                    TextInput::make('position_x')
                        ->label('Position X (e.g., 5%, 15%, 50px)')
                        ->required()
                        ->default('50%')
                        ->maxLength(10),
                    TextInput::make('position_bottom')
                        ->label('Position Bottom (e.g., 0px, -80px, 20px)')
                        ->required()
                        ->default('0px')
                        ->maxLength(10),
                ])->columns(3),

            Section::make('Settings')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Active')
                        ->required()
                        ->default(true),
                    TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->required()
                        ->numeric()
                        ->default(0),
                ])->columns(2),
        ];
    }
}
