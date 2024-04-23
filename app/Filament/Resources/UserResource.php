<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\UserRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->whereNotNull('email_verified_at');
    // }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('email_verified_at'),
                IconEntry::make('is_admin')
                    ->boolean(),
                TextEntry::make('updated_at'),
                TextEntry::make('created_at'),
            ]);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Username')
                    ->disableAutocomplete(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Name')
                    ->disableAutocomplete()
                    ->helperText(' Full name here, including any middle names.'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->placeholder('Email')
                    ->disableAutocomplete()
                    ->maxLength(255),


                Forms\Components\TextInput::make('password')
                    ->default('password')
                    ->required()
                    ->minLength(8)
                    ->placeholder('Password')
                    ->helperText('Default Password is : password')
                    ->dehydrateStateUsing(static fn(null|string $state):
                        null|string =>
                        filled($state) ? Hash::make($state): null,
                )->required(static fn (Page $livewire): string =>
                   $livewire instanceof CreateUser,
                )->dehydrated(static fn(null|string $state): bool =>
                        filled($state),
                    )->label(static fn(PAge $livewire): string =>
                        ($livewire instanceof EditUser) ? 'New Password' : 'Password'
                    ),
                    Hidden::make('email_verified_at')

                    ->default(fn() => now()),

                    Hidden::make('is_admin')
                    ->required()
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            UserRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
