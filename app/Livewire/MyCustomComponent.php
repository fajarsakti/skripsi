<?php

namespace App\Livewire;

use Livewire\Component;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;

class MyCustomComponent extends MyProfileComponent
{
    protected string $view = "livewire.my-custom-component";

    public $user;

    public static $sort = 10;

    public array $data;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('phone')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->label('Phone Number'),
                TextInput::make('address')
                    ->label('Address')
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $keys = ['phone', 'address'];
        $data = collect($this->form->getState())->only($keys)->all();
        $this->user->update($data);

        \Filament\Notifications\Notification::make()
            ->title('Profile saved!')
            ->success()
            ->send();
    }

    public function mount()
    {
        $this->user = Auth::user();
    }
}

