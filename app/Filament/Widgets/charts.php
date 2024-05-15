<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class charts extends ChartWidget
{
    protected static ?string $heading = 'Interest Chart';

    protected function getData(): array
    {
        $interests = User::select('topic')
                        ->whereNotNull('topic')
                        ->groupBy('topic')
                        ->orderByRaw('COUNT(*) DESC')
                        ->pluck('topic');

        $interestCounts = [];
        $colors = ['#3490dc', '#9561e2', '#38c172', '#e3342f', '#f6993f', '#ffd700', '#6cb2eb', '#f66d9b', '#5a53db', '#2a5a75'];

        foreach ($interests as $index => $interest) {
            $interestCounts[] = User::where('topic', $interest)->count();
        }

        return [
            'labels' => $interests,
            'datasets' => [
                [
                    'label' => 'Interest Count',
                    'data' => $interestCounts,
                    'backgroundColor' => $colors,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
