<?php

namespace App\Filament\Resources\Blogs\Schemas;

use App\Models\Image;
use Dom\Text;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\RichEditor;
use GuzzleHttp\Psr7\UploadedFile;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
class BlogsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')->schema([
                TextInput::make('title')
                    ->label('Tiêu đề bài viết')
                    ->required()
                    ->placeholder('Nhập tiêu đề bài viết')
                    ->maxLength(255)
                    ->columnSpan(1),
                TextInput::make('excerpt')
                    ->label('Trích dẫn')
                    ->placeholder('Nhập trích dẫn')
                    ->maxLength(255)
                    ->columnSpan(1),
                TextInput::make('slug')
                    ->label('Slug')
                    ->placeholder('Nhập slug (đường dẫn thân thiện với SEO)')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                RichEditor::make('content')
                    ->label('Nội dung bài viết')
                    ->placeholder('Nhập nội dung bài viết')
                    ->required()
                    ->columnSpan(2),
                FileUpload::make('image')
                    ->label('Hình ảnh đại diện')
                    ->image()
                    ->maxSize(1024 * 1024 * 5) // 5MB
                    ->disk('public')
                    ->required()
                    ->columnSpan(2),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Bản nháp',
                        'published' => 'Đã xuất bản',
                    ])
                    ->required()
                    ->default('draft')
                    ->columnSpan(2),
            ])->columns(2)
                ->columnSpan(2)
            ])->columns(4);
    }
}
