<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class Appearance extends Component
{
    public function mount()
    {
        session(['theme' => 'light']);
    }

    public function render()
    {
        return view('livewire.settings.appearance');
    }
}
