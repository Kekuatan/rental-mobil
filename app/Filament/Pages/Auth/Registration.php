<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register ;

class Registration extends Register
{
    public function form(Form $form): Form
    {


        return $form
            ->schema([

                $this->getNameFormComponent(),
                TextInput::make('phone_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('driving_license_number')
                    ->maxLength(255),
                Textarea::make('address'),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

}
