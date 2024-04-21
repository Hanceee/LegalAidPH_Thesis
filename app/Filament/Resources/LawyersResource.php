<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Lawyers;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LawyersResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LawyersResource\RelationManagers;

class LawyersResource extends Resource
{
    protected static ?string $model = Lawyers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('contact')
                    ->required()
                    ->maxLength(300),
                Forms\Components\TextInput::make('specializations')
                    ->required()
                    ->maxLength(200),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('experience')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->groups([
            Group::make('location'),
        ])
        ->defaultGroup('location')
        ->groupingSettingsHidden()

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specializations')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('experience')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLawyers::route('/'),
            'create' => Pages\CreateLawyers::route('/create'),
            'view' => Pages\ViewLawyers::route('/{record}'),
            'edit' => Pages\EditLawyers::route('/{record}/edit'),
        ];
    }
}
