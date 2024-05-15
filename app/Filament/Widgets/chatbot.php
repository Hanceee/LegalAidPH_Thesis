<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\ChatbotConfiguration;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class chatbot extends BaseWidget
{    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
ChatbotConfiguration::query()            )
            ->columns([
                Tables\Columns\TextColumn::make('temperature')
                ->numeric()
                ->description(""),
                TextColumn::make('model_name')->size(TextColumn\TextColumnSize::Large),
                TextColumn::make('model_details')->wrap()
                 ,

                    Tables\Columns\TextColumn::make('system_instruction')
                    ->wrap()
                    ->words(20)
                    ,
                    Tables\Columns\TextColumn::make('txt_file')
                    ->wrap()
                    ->words(20)
                    ,
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),            ]);
    }
}
