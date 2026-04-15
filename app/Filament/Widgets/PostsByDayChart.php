<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;

class PostsByDayChart extends ChartWidget
{
    protected ?string $heading = 'Bài đăng 7 ngày gần nhất';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect(range(6, 0))
            ->map(fn (int $dayOffset) => now()->subDays($dayOffset)->toDateString())
            ->push(now()->toDateString())
            ->values();

        $counts = Post::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as aggregate')
            ->whereDate('created_at', '>=', now()->subDays(6)->toDateString())
            ->groupBy('day')
            ->pluck('aggregate', 'day');

        $labels = $days->map(fn (string $day) => date('d/m', strtotime($day)))->all();
        $series = $days->map(fn (string $day) => (int) ($counts[$day] ?? 0))->all();

        return [
            'datasets' => [
                [
                    'label' => 'Số bài đăng',
                    'data' => $series,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
