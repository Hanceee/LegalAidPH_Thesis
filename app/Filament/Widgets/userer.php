<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class userer extends BaseWidget
{    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
User::query()         )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('username')
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('chats_count')
                ->counts('chats')
                ->searchable(),
                Tables\Columns\TextColumn::make('notifications_count')
                ->counts('notifications')
                ->searchable()
                ->label("Warning Count"),
            Tables\Columns\TextColumn::make('email_verified_at')
                ->dateTime()
                ->sortable()
                ->placeholder('Not Verified.'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\IconColumn::make('is_admin')
                ->boolean(),
            Tables\Columns\TextColumn::make('deleted_at')
                ->label("Blocked at")
                ->placeholder('Not Blocked/Restricted.')            ]);
    }
}
