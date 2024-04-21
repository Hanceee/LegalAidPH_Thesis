<?php

namespace App\Filament\Resources\LawyersResource\Pages;

use App\Filament\Resources\LawyersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLawyers extends ViewRecord
{
    protected static string $resource = LawyersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
