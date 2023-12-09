<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }
}
