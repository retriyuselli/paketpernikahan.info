<?php

namespace App\Filament\Admin\Resources\Blogs;

use App\Filament\Admin\Resources\Blogs\Pages\CreateBlog;
use App\Filament\Admin\Resources\Blogs\Pages\EditBlog;
use App\Filament\Admin\Resources\Blogs\Pages\ListBlogs;
use App\Models\Blog;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $modelLabel = 'Blog Post';
    protected static ?string $pluralModelLabel = 'Blog Posts';

    public static function getRecordRouteKeyName(): ?string
    {
        return 'slug';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-newspaper';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(Schemas\BlogForm::schema());
    }

    public static function table(Table $table): Table
    {
        return Tables\BlogsTable::table($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBlogs::route('/'),
            'create' => CreateBlog::route('/create'),
            'edit'   => EditBlog::route('/{record}/edit'),
        ];
    }
}
