<?php

namespace App\Filament\Widgets;

use App\Models\ListingPackage;
use Filament\Widgets\ChartWidget;

class RevenueByMonthChart extends ChartWidget
{
    protected ?string $heading = 'Doanh thu gói đăng tin (12 tháng)';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $months = collect(range(11, 0))
            ->map(fn (int $offset) => now()->startOfMonth()->subMonths($offset))
            ->values();

        $startMonth = $months->first()->format('Y-m-01');

        $rawRevenue = ListingPackage::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(price) as revenue")
            ->where('price', '>', 0)
            ->whereDate('created_at', '>=', $startMonth)
            ->groupBy('ym')
            ->pluck('revenue', 'ym');

        $labels = $months->map(fn ($month) => $month->format('m/Y'))->all();
        $series = $months->map(function ($month) use ($rawRevenue) {
            $key = $month->format('Y-m');

            return (float) ($rawRevenue[$key] ?? 0);
        })->all();

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu (VND)',
                    'data' => $series,
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
