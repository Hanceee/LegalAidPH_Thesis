<?php

namespace App\Filament\Resources\LawyersResource\Pages;

use App\Filament\Resources\LawyersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLawyers extends EditRecord
{
    protected static string $resource = LawyersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
