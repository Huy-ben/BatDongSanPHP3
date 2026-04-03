<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
return $schema
    ->schema([
        TextInput::make('category_name')
            ->label('Tên danh mục')
            ->placeholder('Nhập tên danh mục...')
            ->required()
            ->maxLength(255),

        TextInput::make('parent_id')
            ->label('Danh mục cha')
            ->placeholder('Nhập ID danh mục cha'),

        Textarea::make('description')
            ->label('Mô tả')
            ->placeholder('Nhập mô tả...')
            ->rows(4),
    ]);
    }
}
