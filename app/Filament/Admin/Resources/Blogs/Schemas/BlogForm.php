<?php

namespace App\Filament\Admin\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;

class BlogForm
{
    public static function schema(): array
    {
        return [
            Section::make('Konten')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, callable $set) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug($state));
                            }
                        })
                        ->columnSpanFull(),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->columnSpanFull(),

                    Select::make('category')
                        ->label('Kategori')
                        ->options([
                            'Wedding Ideas'       => 'Wedding Ideas',
                            'Relationship Tips'   => 'Relationship Tips',
                            'Venue & Decoration'  => 'Venue & Decoration',
                            'Budget Planning'     => 'Budget Planning',
                            'Fashion & Beauty'    => 'Fashion & Beauty',
                            'Food & Catering'     => 'Food & Catering',
                            'Photography'         => 'Photography',
                            'Honeymoon'           => 'Honeymoon',
                        ])
                        ->searchable()
                        ->nullable(),

                    Textarea::make('excerpt')
                        ->label('Ringkasan')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Tampil sebagai preview di listing blog.')
                        ->columnSpanFull(),

                    FileUpload::make('cover_image')
                        ->label('Gambar Cover')
                        ->image()
                        ->disk('public')
                        ->directory('blogs')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:2')
                        ->helperText('Rekomendasi: rasio 3:2 (landscape), min. 900×600px')
                        ->columnSpanFull(),

                    RichEditor::make('content')
                        ->label('Konten')
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('blogs/attachments')
                        ->columnSpanFull(),

                    TagsInput::make('tags')
                        ->label('Tags')
                        ->separator(',')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Publikasi')
                ->schema([
                    Toggle::make('is_published')
                        ->label('Publikasikan')
                        ->default(false),

                    DateTimePicker::make('published_at')
                        ->label('Tanggal Publikasi')
                        ->nullable()
                        ->helperText('Kosongkan untuk memakai waktu saat dipublikasikan.'),

                    TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),

                    Select::make('author_id')
                        ->label('Penulis')
                        ->relationship(
                            name: 'author',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->role('author'),
                        )
                        ->searchable()
                        ->nullable()
                        ->helperText('Hanya menampilkan user dengan role "author".'),
                ])->columns(2),
        ];
    }
}
