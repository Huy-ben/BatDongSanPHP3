<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Category;
use App\Models\ListingPackage;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalPosts = Post::query()->count();
        $publishedPosts = Post::query()->where('status', '1')->count();
        $totalCategories = Category::query()->count();
        $activeCategories = Category::query()->where('status', '1')->count();
        $totalUsers = User::query()->count();
        $todayUsers = User::query()->whereDate('created_at', today())->count();
        $publishedBlogs = Blog::query()->where('status', 'published')->count();
        $monthlyRevenue = (float) ListingPackage::query()
            ->where('price', '>', 0)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('price');
        $yearlyRevenue = (float) ListingPackage::query()
            ->where('price', '>', 0)
            ->whereYear('created_at', now()->year)
            ->sum('price');

        return [
            Stat::make('Tổng bài đăng', number_format($totalPosts))
                ->description("{$publishedPosts} bài đang hiển thị")
                ->color('primary')
                ->icon('heroicon-o-document-text'),
            Stat::make('Danh mục', number_format($totalCategories))
                ->description("{$activeCategories} danh mục hoạt động")
                ->color('warning')
                ->icon('heroicon-o-rectangle-stack'),
            Stat::make('Người dùng', number_format($totalUsers))
                ->description("+{$todayUsers} hôm nay")
                ->color('success')
                ->icon('heroicon-o-users'),
            Stat::make('Blog đã xuất bản', number_format($publishedBlogs))
                ->description('Nội dung tin tức đang hoạt động')
                ->color('info')
                ->icon('heroicon-o-newspaper'),
            Stat::make('Doanh thu tháng', number_format((int) round($monthlyRevenue)).' đ')
                ->description('Tổng giá trị gói trả phí trong tháng hiện tại')
                ->color('success')
                ->icon('heroicon-o-banknotes'),
            Stat::make('Doanh thu năm', number_format((int) round($yearlyRevenue)).' đ')
                ->description('Tổng giá trị gói trả phí trong năm hiện tại')
                ->color('primary')
                ->icon('heroicon-o-chart-bar-square'),
        ];
    }
}
