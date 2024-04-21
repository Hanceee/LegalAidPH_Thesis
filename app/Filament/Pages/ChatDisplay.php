<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ArchiveResource;
use App\Models\Chat;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\ActionSize;

class ChatDisplay extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static string $view = 'filament.pages.chat-display';

    protected function getHeaderActions(): array
{

    return [
        ActionGroup::make([

            Action::make('Manage Archived Chats')
            ->url(fn (): string => ArchiveResource::getUrl())
            ->openUrlInNewTab()
            ->icon('heroicon-o-archive-box')

                ,
                Action::make('Archive all chats')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->action(function () {
                        Chat::query()->update(['is_archived' => 1]);
                    }),
                Action::make('Delete all chats')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->action(function () {
                        Chat::query()->delete();
                    }),
            ])
            ->label('Settings')
            ->icon('heroicon-o-cog-6-tooth')
            ->size(ActionSize::Small)
            ->color('primary')
            ->button()
    ];
}
}
