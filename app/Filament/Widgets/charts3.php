<?php

namespace App\Filament\Widgets;

use App\Models\ChatMessage;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class charts3 extends ChartWidget
{
    protected static ?string $heading = 'Messages Exchanged';
    protected int | string | array $columnSpan = 'full';


    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(30); // Change to subWeeks(4) for weeks, subMonths(1) for months
        $endDate = Carbon::now();

        $messagesExchanged = ChatMessage::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($message) {
                return $message->created_at->format('Y-m-d'); // Change to 'Y-W' for weeks, 'Y-m' for months
            })
            ->map(function ($group) {
                return $group->count();
            });

        return [
            'labels' => $messagesExchanged->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Messages Exchanged',
                    'data' => $messagesExchanged->values()->toArray(),
                    'backgroundColor' => 'rgba(52, 144, 220, 0.2)',
                    'borderColor' => '#3490dc',
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
