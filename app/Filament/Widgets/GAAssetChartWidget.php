<?php

namespace App\Filament\Widgets;

use App\Models\GA\GaAssetCategory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class GAAssetChartWidget extends ChartWidget
{
    protected static ?string $heading = 'GA Assets Distribution (Bar)';

    protected static ?int $sort = 2;

    protected static ?int $columnSpan = 6;

    public static function canView(): bool
    {
        $user = Auth::user();

        return $user && ($user->division?->initial === 'GA' || $user->hasRole('Super Admin'));
    }

    protected function getData(): array
    {
        $categories = GaAssetCategory::withCount('assets')->get();
        $labels = $categories->pluck('name')->toArray();
        $counts = $categories->pluck('assets_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Assets Count',
                    'data' => $counts,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(199, 199, 199, 0.2)',
                        'rgba(83, 102, 255, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                        'rgba(83, 102, 255, 1)',
                    ],
                    'borderWidth' => 1,
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
