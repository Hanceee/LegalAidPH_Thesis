<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class UserCount extends BaseWidget
{
    protected function getStats(): array
    {
        // Get all chat names
        $chatNames = Chat::pluck('name')->toArray();

        // Count occurrences of each word in chat names
        $wordCount = [];
        foreach ($chatNames as $name) {
            $words = explode(' ', $name);
            foreach ($words as $word) {
                $word = strtolower($word); // Convert to lowercase for case-insensitivity
                if (!isset($wordCount[$word])) {
                    $wordCount[$word] = 0;
                }
                $wordCount[$word]++;
            }
        }

        // Find the most common word
        arsort($wordCount);
        $commonChatTopic = key($wordCount);
 // Get the count of soft-deleted or archived users
 $softDeletedUsersCount = User::onlyTrashed()->count();
// Calculate the average messages per chat with two decimal places
$averageMessagesPerChat = number_format(Chat::withCount('chatmessages')->get()->avg('chatmessages_count'), 2);
        // Generate random chart data
        $randomChartData = [];
        for ($i = 0; $i < 9; $i++) {
            $randomChartData[] = rand(1, 50);
        }
  // Get daily chats and daily chat initiated
  $today = Carbon::today();
  $yesterday = Carbon::yesterday();
  $dailyChats = ChatMessage::whereDate('created_at', $today)->count();
        return [
            Stat::make('Users Count', User::count())
                ->chart($randomChartData)
                ->color('primary'),
            Stat::make('Chats Count', Chat::count())
                ->chart($randomChartData)
                ->color('warning'),
                Stat::make('Average Message per chat', $averageMessagesPerChat)
                ->chart($randomChartData)
                ->color('primary'),
            Stat::make('Common chat topic', $commonChatTopic)
                ->chart($randomChartData)
                ->color('warning'),
                Stat::make('Restricted Users', $softDeletedUsersCount)
                ->color('danger')
                ->chart($randomChartData)
                ,Stat::make('Daily Usage', $dailyChats )
                ->color('info')                ->chart($randomChartData)
                ,
        ];
    }
}
