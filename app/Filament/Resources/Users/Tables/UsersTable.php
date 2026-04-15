<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Image;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Họ và tên')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('Số điện thoại')
                    ->placeholder('Chưa có')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Vai trò')
                    ->formatStateUsing(fn ($state): string => match ((string) $state) {
                        '0' => 'Quản trị viên',
                        '1' => 'Người mua',
                        default => 'Người bán',
                    })
                    ->color(fn ($state): string => match ((string) $state) {
                        '0' => 'danger',
                        '1' => 'warning',
                        default => 'info',
                    })
                    ->badge(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state): string => (string) $state === '1' ? 'Đang hoạt động' : 'Bị khóa')
                    ->color(fn ($state): string => (string) $state === '1' ? 'success' : 'danger')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                    SelectFilter::make('role')
                        ->label('Vai trò')
                        ->options([
                            '0' => 'Quản trị viên',
                            '1' => 'Người mua',
                            '2' => 'Người bán',
                        ]),
    
                    SelectFilter::make('status')
                        ->label('Trạng thái')
                        ->options([
                            '1' => 'Đang hoạt động',
                            '0' => 'Bị khóa',
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
