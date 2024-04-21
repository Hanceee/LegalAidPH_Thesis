<?php

namespace App\Filament\Resources\ChatbotConfigurationResource\Pages;

use App\Filament\Resources\ChatbotConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChatbotConfiguration extends ViewRecord
{
    protected static string $resource = ChatbotConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
