<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Widgets;

use App\Models\IT\ITAssetCategory;
use Filament\Widgets\ChartWidget;

class ITAssetDoughnutWidget extends ChartWidget
{
    protected static ?string $heading = 'IT Assets Distribution';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        // Only allow users with ITD division to see this widget
        return auth()->user()?->division?->initial === 'ITD';
    }

    protected function getData(): array
    {
        $categories = ITAssetCategory::withCount('assets')->get();
        $labels = $categories->pluck('name')->toArray();
        $counts = $categories->pluck('assets_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Assets Count',
                    'data' => $counts,
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(199, 199, 199, 0.2)',
                        'rgba(83, 102, 255, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
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
        return 'doughnut';
    }
}
