<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ListingPackagesRelationManager extends RelationManager
{
    protected static string $relationship = 'listingPackages';

    protected static ?string $title = 'Gói đã mua';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('package_name')
                    ->label('Tên gói')
                    ->required()
                    ->maxLength(255),
                Select::make('package_type')
                    ->label('Loại gói')
                    ->required()
                    ->options([
                        '0' => 'Gói thường',
                        '1' => 'Gói VIP',
                        '2' => 'Gói SVIP',
                    ]),
                TextInput::make('price')
                    ->label('Giá')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                DatePicker::make('expiry_date')
                    ->label('Ngày hết hạn')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->required()
                    ->options([
                        '1' => 'Đang hoạt động',
                        '0' => 'Đã khóa',
                    ]),
                Toggle::make('is_featured')
                    ->label('Nổi bật')
                    ->inline(false),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('package_name')
            ->columns([
                TextColumn::make('package_name')
                    ->label('Tên gói')
                    ->searchable(),
                TextColumn::make('package_type')
                    ->label('Loại gói')
                    ->badge()
                    ->color(fn ($state): string => match ((string) $state) {
                        '1' => 'warning',
                        '2' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ((string) $state) {
                        '1' => 'VIP',
                        '2' => 'SVIP',
                        default => 'Thường',
                    }),
                TextColumn::make('price')
                    ->label('Giá')
                    ->numeric(0)
                    ->suffix(' VND')
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->label('Ngày hết hạn')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn ($state): string => (string) $state === '1' ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state): string => (string) $state === '1' ? 'Đang hoạt động' : 'Đã khóa'),
            ])
            ->filters([
                SelectFilter::make('package_type')
                    ->label('Loại gói')
                    ->options([
                        '0' => 'Gói thường',
                        '1' => 'Gói VIP',
                        '2' => 'Gói SVIP',
                    ]),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        '1' => 'Đang hoạt động',
                        '0' => 'Đã khóa',
                    ]),
            ])
            ->headerActions([
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
