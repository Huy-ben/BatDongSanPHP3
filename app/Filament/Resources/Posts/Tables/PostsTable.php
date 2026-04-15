<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnailImage.image_url')
                    ->label('Ảnh')
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.category_name')
                    ->label('Danh mục')
                    ->sortable(),

                TextColumn::make('seller.name')
                    ->label('Người bán')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Giá')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 0, ',', '.') . ' đ')
                    ->sortable(),

                TextColumn::make('area')
                    ->label('Diện tích')
                    ->formatStateUsing(fn ($state): string => rtrim(rtrim(number_format((float) $state, 2, '.', ''), '0'), '.') . ' m²')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ((string) $state) {
                        Post::STATUS_PUBLISHED => 'Đã đăng',
                        Post::STATUS_REJECTED => 'Bị từ chối',
                        Post::STATUS_WAITING => 'Chờ duyệt',
                        default => 'Bản nháp',
                    })
                    ->color(fn ($state): string => match ((string) $state) {
                        Post::STATUS_PUBLISHED => 'success',
                        Post::STATUS_REJECTED => 'danger',
                        Post::STATUS_WAITING => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('images_count')
                    ->label('Số ảnh')
                    ->counts('images')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        Post::STATUS_DRAFT => 'Bản nháp',
                        Post::STATUS_PUBLISHED => 'Đã đăng',
                        Post::STATUS_REJECTED => 'Bị từ chối',
                        Post::STATUS_WAITING => 'Chờ duyệt',
                    ]),
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
