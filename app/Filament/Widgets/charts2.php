<?php

namespace App\Filament\Widgets;

use App\Models\Chat;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class charts2 extends ChartWidget
{
    protected static ?string $heading = 'Chats Initiated';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(30); // Change to subWeeks(4) for weeks, subMonths(1) for months
        $endDate = Carbon::now();

        $chatsInitiated = Chat::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($chat) {
                return $chat->created_at->format('Y-m-d'); // Change to 'Y-W' for weeks, 'Y-m' for months
            })
            ->map(function ($group) {
                return $group->count();
            });

        return [
            'labels' => $chatsInitiated->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Chats Initiated',
                    'data' => $chatsInitiated->values()->toArray(),
                    'borderColor' => '#3490dc',
                    'backgroundColor' => 'rgba(52, 144, 220, 0.2)',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
