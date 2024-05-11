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
use Filament\Forms\Components\Select;

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

                Select::make('topic')
                ->options([
                    'Marriage' => 'Marriage',
                    'Adoption' => 'Adoption',
                    'Child support' => 'Child support',
                    'Annulment' => 'Annulment',
                    'Property rights' => 'Property rights',
                    'Parental authority' => 'Parental authority',
                    'Inheritance' => 'Inheritance',
                    'Legal separation' => 'Legal separation',
                    'Paternity/maternity issues' => 'Paternity/maternity issues',
                    'Spousal support' => 'Spousal support',
                    'Guardianship' => 'Guardianship',
                    'Child custody' => 'Child custody',
                    'Visitation rights' => 'Visitation rights',
                    'Termination of parental rights' => 'Termination of parental rights',
                    'Family mediation' => 'Family mediation',
                    'Succession' => 'Succession',
                    'Parental obligations' => 'Parental obligations',
                    'Child abuse' => 'Child abuse',
                    'Child neglect' => 'Child neglect',
                    'Child abduction' => 'Child abduction',
                    'Parenting plan' => 'Parenting plan',
                    'Separation agreement' => 'Separation agreement',
                    'Adultery' => 'Adultery',
                    'Bigamy' => 'Bigamy',
                    'Abandonment' => 'Abandonment',
                    'Emancipation' => 'Emancipation',
                    'Foster care' => 'Foster care',
                    'Dependency and neglect' => 'Dependency and neglect',
                    'Surrogacy' => 'Surrogacy',
                    'Juvenile delinquency' => 'Juvenile delinquency',
                    'Parental kidnapping' => 'Parental kidnapping',
                    'Domestic partnership dissolution' => 'Domestic partnership dissolution',
                    'Child relocation' => 'Child relocation',
                    'Grandparent visitation rights' => 'Grandparent visitation rights',
                    'Name change' => 'Name change',
                    'Prenuptial agreements' => 'Prenuptial agreements',
                    'Postnuptial agreements' => 'Postnuptial agreements',
                    'Spousal maintenance' => 'Spousal maintenance',
                    'Domestic violence restraining order' => 'Domestic violence restraining order',
                    'Marital property division' => 'Marital property division',
                    'Community property' => 'Community property',
                    'Divorce' => 'Divorce',
                    'Legal separation' => 'Legal separation',
                    'Annulment' => 'Annulment',
                    'No-fault divorce' => 'No-fault divorce',
                    'Contested divorce' => 'Contested divorce',
                    'Uncontested divorce' => 'Uncontested divorce',
                    'Fault-based divorce' => 'Fault-based divorce',
                    'Alimony' => 'Alimony',
                    'Spousal support' => 'Spousal support',
                    'Alimony modification' => 'Alimony modification',
                    'Alimony termination' => 'Alimony termination',
                    'Alimony enforcement' => 'Alimony enforcement',
                    'Alimony calculation' => 'Alimony calculation',
                    'Child support' => 'Child support',
                    'Child custody' => 'Child custody',
                    'Visitation rights' => 'Visitation rights',
                    'Property division' => 'Property division',
                    'Pre-nuptial agreement' => 'Pre-nuptial agreement',
                    'Post-nuptial agreement' => 'Post-nuptial agreement'
    ]) ->required()->searchable()->label('Family Law Topic')->helperText("Please select a topic related to Family Law in the Philippines to personalize your assistance."),
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
