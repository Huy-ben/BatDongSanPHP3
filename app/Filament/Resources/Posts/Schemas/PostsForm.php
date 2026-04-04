<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PostsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->label('Tiêu đề bài đăng')
                    ->required()
                    ->maxLength(255),

                Select::make('seller_id')
                    ->label('Người bán')
                    ->relationship('seller', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'category_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('price')
                    ->label('Giá bán')
                    ->numeric()
                    ->required()
                    ->prefix('₫'),

                TextInput::make('area')
                    ->label('Diện tích')
                    ->numeric()
                    ->required()
                    ->suffix('m²'),

                TextInput::make('address')
                    ->label('Địa chỉ')
                    ->required()
                    ->maxLength(255),

                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Hiện',
                        0 => 'Ẩn',
                    ])
                    ->default(1)
                    ->required(),

                FileUpload::make('images')
                    ->label('Hình ảnh bài đăng')
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
