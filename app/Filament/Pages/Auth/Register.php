<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseEditProfile;

class Register extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([ TextInput::make('username')
            ->required()
            ->maxLength(255),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
