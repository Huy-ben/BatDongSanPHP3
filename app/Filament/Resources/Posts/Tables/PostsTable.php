<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

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
                Action::make('approve')
                    ->label('Duyệt')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Post $record): bool => $record->status === Post::STATUS_WAITING)
                    ->action(fn (Post $record) => $record->update(['status' => Post::STATUS_PUBLISHED])),
                Action::make('reject')
                    ->label('Từ chối')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('reject_reason')
                            ->label('Lý do từ chối')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000),
                    ])
                    ->visible(fn (Post $record): bool => $record->status === Post::STATUS_WAITING)
                    ->action(function (Post $record, array $data): void {
                        $record->update(['status' => Post::STATUS_REJECTED]);

                        DB::table('notifications')->insert([
                            'user_id' => $record->seller_id,
                            'title' => 'Bài đăng bị từ chối',
                            'content' => "Bài đăng '{$record->title}' đã bị từ chối. Lý do: {$data['reject_reason']}",
                            'is_read' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }),
                Action::make('markWaiting')
                    ->label('Chờ duyệt')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Post $record): bool => $record->status === Post::STATUS_DRAFT)
                    ->action(fn (Post $record) => $record->update(['status' => Post::STATUS_WAITING])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approveSelected')
                        ->label('Duyệt đã chọn')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn (Post $record) => $record->update(['status' => Post::STATUS_PUBLISHED]))),
                    BulkAction::make('rejectSelected')
                        ->label('Từ chối đã chọn')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Textarea::make('reject_reason')
                                ->label('Lý do từ chối')
                                ->required()
                                ->rows(4)
                                ->maxLength(1000),
                        ])
                        ->action(function ($records, array $data): void {
                            $now = now();

                            $records->each(function (Post $record) use ($data, $now): void {
                                $record->update(['status' => Post::STATUS_REJECTED]);

                                DB::table('notifications')->insert([
                                    'user_id' => $record->seller_id,
                                    'title' => 'Bài đăng bị từ chối',
                                    'content' => "Bài đăng '{$record->title}' đã bị từ chối. Lý do: {$data['reject_reason']}",
                                    'is_read' => false,
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ]);
                            });
                        }),
                    BulkAction::make('markWaitingSelected')
                        ->label('Đặt chờ duyệt')
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(fn (Post $record) => $record->update(['status' => Post::STATUS_WAITING]))),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
