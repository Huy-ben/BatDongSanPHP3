<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use App\Models\Image;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('category_name')
                    ->label('Tên danh mục')
                    ->placeholder('Nhập tên danh mục')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('parent_id')
                    ->label('Danh mục cha')
                    ->placeholder('Danh mục gốc')
                    ->relationship('parentCategory', 'category_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        1 => 'Hiện',
                        0 => 'Ẩn',
                    ])
                    ->default(1)
                    ->required(),

                Textarea::make('description')
                    ->label('Mô tả')
                    ->placeholder('Nhập mô tả danh mục')
                    ->rows(4)
                    ->required(),
                FileUpload::make('image')
                    ->label('Ảnh danh mục')
                    ->image()
                    ->disk('public')
                    ->directory('categories')
                    ->nullable(),
            ]);

    }
}
