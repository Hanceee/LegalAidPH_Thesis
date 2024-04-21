<?php

namespace App\Filament\Pages\Auth;

use delete;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Actions;
use Filament\Pages\Actions\EditAction;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('username')
                //     ->required()
                //     ->maxLength(255),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),


                $this->getPasswordConfirmationFormComponent(),
                Actions::make([
                    Action::make('Delete Account?')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->icon('heroicon-m-x-mark')
                    ->size(ActionSize::Small)
                    ->action(fn () => User::where('id', auth()->id())->delete()),


                ])->alignment(Alignment::Center),

            ]);


    }


}
