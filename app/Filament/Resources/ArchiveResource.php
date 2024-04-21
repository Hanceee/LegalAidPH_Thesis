<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Chat;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use App\Filament\Resources\ArchiveResource\Pages;
use App\Models\Post;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ArchiveResource\RelationManagers;
use Illuminate\Database\Eloquent\Collection;

class ArchiveResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static bool $shouldRegisterNavigation = false;


    protected static ?string  $pluralModelLabel = 'Archived Chats';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id())->where('is_archived', 1);
    }

    public static function table(Table $table): Table
    {

        return $table

            ->columns([

                TextColumn::make('name')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->color('primary'),
                TextColumn::make('created_at')
                ->label('Created At'),


                ])

            ->filters([
                //
            ])
            ->actions([
                Action::make('Restore')
                ->icon('heroicon-o-archive-box-arrow-down')
                ->color('secondar')->iconButton()
                ->action(function (Chat $record) {
                    $record->is_archived = 0;
                    $record->save();
                }),


                Tables\Actions\DeleteAction::make()->icon('heroicon-o-trash')->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    RestoreBulkAction::make()
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->action(function (Chat $record) {
                        $record->is_archived = 0;
                        $record->save();
                    }),
                    Tables\Actions\DeleteBulkAction::make()->icon('heroicon-o-trash'),
                ]),
            ]);
    }

    public static function getPages(): array
    {

        return [
            'index' => Pages\ManageArchives::route('/'),
        ];
    }
}
