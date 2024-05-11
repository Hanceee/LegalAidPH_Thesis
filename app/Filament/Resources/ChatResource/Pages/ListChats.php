<?php

namespace App\Filament\Resources\ChatResource\Pages;

use App\Filament\Resources\ChatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChats extends ListRecords
{
    protected static string $resource = ChatResource::class;
    protected ?string $heading = "Chats with Profanities";

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
