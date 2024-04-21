<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Chat;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ChatResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ChatResource\RelationManagers;
use App\Filament\Resources\ChatResource\RelationManagers\ChatMessagesRelationManager;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_archived', 0);
    }

    public static function canCreate(): bool
    {
       return false;
    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('user.name'),
                TextEntry::make('updated_at'),
                TextEntry::make('created_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable()
                    ,
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Chat'),
                Tables\Columns\TextColumn::make('chat_messages_count')
                    ->counts('chatMessages')
                    ->label('Messages count')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('updated_at', 'desc')



            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ChatMessagesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChats::route('/'),
            'view' => Pages\ViewChat::route('/{record}'),
        ];
    }
}
