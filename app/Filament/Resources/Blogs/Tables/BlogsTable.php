<?php

namespace App\Filament\Resources\Blogs\Tables;

use App\Enums\BlogStatus;
use App\Models\Image;
use BaconQrCode\Renderer\Color\Gray;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Tác giả')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('excerpt')
                    ->label('Trích dẫn')
                    ->searchable()
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->label('Ảnh bài viết')
                    ->disk('public'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn($state) => BlogStatus::from($state)->getLabel())
                    ->badge()
                    ->color(fn($state) => BlogStatus::from($state)->getBadgeColor())
                ,
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
