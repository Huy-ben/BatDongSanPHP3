<?php
namespace App\Enums;
enum BlogStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Bản nháp',
            self::PUBLISHED => 'Đã xuất bản',
        };
    }
    public function getBadgeColor(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PUBLISHED  => 'success',
        };
    }
}
