<?php

namespace App\Filament\Resources\ChatbotConfigurationResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ChatbotConfigurationResource;

class ListChatbotConfigurations extends ListRecords
{
    protected static string $resource = ChatbotConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make("OpenAi Credit Usage")
            ->label("Credit Usage")
            ->url(fn (): string => 'https://platform.openai.com/usage')
            ->openUrlInNewTab(),
            Actions\CreateAction::make()->label("New Configuration"),



        ];
    }
}
