<?php

namespace App\Filament\Resources\LawyersResource\Pages;

use App\Filament\Resources\LawyersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLawyers extends ListRecords
{
    protected static string $resource = LawyersResource::class;
    protected ?string $heading = "Lawyer List for Chatbot's Knowledge Base";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
