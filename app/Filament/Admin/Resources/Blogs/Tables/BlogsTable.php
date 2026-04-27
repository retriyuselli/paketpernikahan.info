<?php

namespace App\Filament\Admin\Resources\Blogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BlogsTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->width(80)
                    ->height(54)
                    ->defaultImageUrl(fn ($record) => 'https://picsum.photos/seed/blog-' . $record->id . '/80/54'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('author.name')
                    ->label('Penulis')
                    ->placeholder('—'),

                ToggleColumn::make('is_published')
                    ->label('Publikasi'),

                TextColumn::make('published_at')
                    ->label('Tgl. Publikasi')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Status Publikasi')
                    ->trueLabel('Dipublikasikan')
                    ->falseLabel('Draft'),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Wedding Ideas'      => 'Wedding Ideas',
                        'Relationship Tips'  => 'Relationship Tips',
                        'Venue & Decoration' => 'Venue & Decoration',
                        'Budget Planning'    => 'Budget Planning',
                        'Fashion & Beauty'   => 'Fashion & Beauty',
                        'Food & Catering'    => 'Food & Catering',
                        'Photography'        => 'Photography',
                        'Honeymoon'          => 'Honeymoon',
                    ]),
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
