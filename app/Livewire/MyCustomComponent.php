<?php

namespace App\Livewire;

use Livewire\Component;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class MyCustomComponent extends MyProfileComponent
{
    protected string $view = "livewire.my-custom-component";

    public static $sort = 10;

    public array $data;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('phone'),
                TextInput::make('address')
            ])
            ->statePath('data');
    }
}
