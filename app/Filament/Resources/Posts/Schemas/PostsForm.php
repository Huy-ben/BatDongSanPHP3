<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class PostsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
               TextInput::make('title')
                    ->label('Tiêu đề bài đăng')
                    ->hiddenOn('edit')
                    ->required()
                    ->maxLength(255),

                Select::make('seller_id')
                    ->label('Người bán')
                    ->hiddenOn('edit')
                    ->relationship('seller', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('category_id')
                    ->label('Danh mục')
                    ->hiddenOn('edit')
                    ->relationship('category', 'category_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('price')
                    ->label('Giá bán')
                    ->hiddenOn('edit')
                    ->numeric()
                    ->required()
                    ->prefix('₫'),

                TextInput::make('area')
                    ->label('Diện tích')
                    ->hiddenOn('edit')
                    ->numeric()
                    ->required()
                    ->suffix('m²'),

                TextInput::make('address')
                    ->label('Địa chỉ')
                    ->hiddenOn('edit')
                    ->required()
                    ->maxLength(255),

                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        Post::STATUS_DRAFT => 'Bản nháp',
                        Post::STATUS_PUBLISHED => 'Đã đăng',
                        Post::STATUS_REJECTED => 'Bị từ chối',
                        Post::STATUS_WAITING => 'Chờ duyệt',
                    ])
                    ->default(Post::STATUS_DRAFT)
                    ->required(),

                FileUpload::make('images')
                    ->label('Hình ảnh bài đăng')
                    ->hiddenOn('edit')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->disk('public')
                    ->directory('posts')
                    ->maxFiles(20)
                    ->helperText('Ảnh đầu tiên trong danh sách sẽ được lưu là ảnh chính (thumbnail).')
                    ->default(fn (?Post $record): array => $record
                        ? $record->images()
                            ->orderByDesc('is_thumbnail')
                            ->orderBy('id')
                            ->pluck('image_url')
                            ->all()
                        : []),
            ]);
    }
}
