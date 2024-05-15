<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ChatbotConfiguration;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ChatbotConfigurationResource\Pages;
use App\Filament\Resources\ChatbotConfigurationResource\RelationManagers;

class ChatbotConfigurationResource extends Resource
{
    protected static ?string $model = ChatbotConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
       return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('model_name')->disabled()
                ->columnSpanFull()
                ,
                Forms\Components\Textarea::make('model_details')->disabled()
                ->columnSpanFull()
                ->autosize()
                ,
                Forms\Components\TextInput::make('temperature')
                    ->required()
                    ->numeric()
                    ->columnSpanFull()
                    ->default(1.00)
                    ->helperText("Low (0) is predictable, medium (0.5-1.0) balanced, high (>1) creative randomness."),

                Forms\Components\Textarea::make('system_instruction')
                    ->required()
                    ->columnSpanFull()
                    ->autosize()

                    ->helperText("Guides AI behavior, influencing context, tasks, or generation parameters"),

                    Forms\Components\Textarea::make('txt_file')->disabled()
                    ->label("Chatbot Data Source")
                    ->required()
                    ->columnSpanFull()
                    ->autosize()

                    ->hint("The chatbot's knowledge base in a .txt file"),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->toggleable(isToggledHiddenByDefault: true),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChatbotConfigurations::route('/'),
            'create' => Pages\CreateChatbotConfiguration::route('/create'),
            'view' => Pages\ViewChatbotConfiguration::route('/{record}'),
            'edit' => Pages\EditChatbotConfiguration::route('/{record}/edit'),
        ];
    }
}
