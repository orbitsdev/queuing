<?php

namespace App\Livewire;

use Livewire\Component;

class TestPage extends Component
{
    public function agree()
    {
        session()->flash('message', 'You have agreed to the terms.');
    }
    
    public function render()
    {
        return view('livewire.test-page');
    }
}
