<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Họ và tên')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('phone_number')
                    ->label('Số điện thoại')
                    ->placeholder('Chưa có')
                    ->tel(),
                Select::make('role')
                    ->label('Vai trò')
                    ->options([
                        '0' => 'Quản trị viên',
                        '1' => 'Người mua',
                        '2' => 'Người bán',
                    ])
                    ->default('2')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        '0' => 'Bị khóa',
                        '1' => 'Đang hoạt động',
                    ])
                    ->default('1')
                    ->required(),
            ]);
    }
}
