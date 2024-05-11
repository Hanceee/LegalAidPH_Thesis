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
    $badWords = [
        // English
        'fuck',
        'shit',
        'asshole',
        'bitch',
        'bastard',
        'damn',
        'crap',
        'cock',
        'dick',
        'pussy',
        'whore',
        'slut',
        'cunt',
        'motherfucker',
        'twat',
        'bollocks',
        'arsehole',
        'douchebag',
        'wanker',
        // Tagalog
        'putangina',
        'gago',
        'tangina',
        'buwisit',
        'pakshet',
        'ulol',
        'lintik',
        'hayop',
        'tanga',
        'sira-ulo',
        'sutil',
        'ungas',
        'inutil',
        'gunggong',
        'gaga',
        'bakla',
        'bayot',
        'bobo',
        'hinayupak',
        'ulupong',
        'leche',
        'hudas',
        'tarantado',
        'bwisit',
        'bwiset',
        'tae',
        'uto-uto',
        'yawa',
        'demonyo',
        'buang',
        'amputa',
        // Spanish (common in Tagalog slang)
        'putamadre',
        'hijo de puta',
        'pucha',
        'pakyu',
        // Add more profanity words as needed
    ];


    return parent::getEloquentQuery()
        ->where('is_archived', 0)
        ->whereHas('chatMessages', function ($query) use ($badWords) {
            $query->where(function ($q) use ($badWords) {
                foreach ($badWords as $word) {
                    $q->orWhere('content', 'REGEXP', '[[:<:]]' . $word . '[[:>:]]');
                }
            });
        });
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
                Tables\Actions\DeleteAction::make()->label("Warning and Delete"),
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
