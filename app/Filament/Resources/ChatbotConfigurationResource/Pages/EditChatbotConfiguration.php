<?php

namespace App\Filament\Resources\ChatbotConfigurationResource\Pages;

use App\Filament\Resources\ChatbotConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChatbotConfiguration extends EditRecord
{
    protected static string $resource = ChatbotConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
