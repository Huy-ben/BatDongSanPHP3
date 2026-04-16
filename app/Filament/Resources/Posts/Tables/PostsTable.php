<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Notification;
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
                    ->action(function (Post $record): void {
                        $record->update(['status' => Post::STATUS_PUBLISHED]);

                        self::createPostApprovalNotification($record);
                    }),
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

                        self::createPostRejectionNotification($record, (string) $data['reject_reason']);
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
                        ->action(function ($records): void {
                            $records->each(function (Post $record): void {
                                $record->update(['status' => Post::STATUS_PUBLISHED]);

                                self::createPostApprovalNotification($record);
                            });
                        }),
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
                            $records->each(function (Post $record) use ($data): void {
                                $record->update(['status' => Post::STATUS_REJECTED]);

                                self::createPostRejectionNotification($record, (string) $data['reject_reason']);
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

    private static function createPostApprovalNotification(Post $post): void
    {
        Notification::query()->create([
            'user_id' => $post->seller_id,
            'title' => 'Bài đăng đã được duyệt',
            'content' => "Bài đăng '{$post->title}' đã được duyệt và đang hiển thị công khai.",
            'is_read' => false,
        ]);
    }

    private static function createPostRejectionNotification(Post $post, string $reason): void
    {
        Notification::query()->create([
            'user_id' => $post->seller_id,
            'title' => 'Bài đăng bị từ chối',
            'content' => "Bài đăng '{$post->title}' đã bị từ chối. Lý do: {$reason}",
            'is_read' => false,
        ]);
    }
}
