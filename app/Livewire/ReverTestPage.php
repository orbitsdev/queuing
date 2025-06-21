<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ReverTestPage extends Component
{
    public $count = 0;
#[On('increaseCount')]
public function increaseCount()
{
    $this->count++;
}

#[On('decreaseCount')]
public function decreaseCount($data)
{
    // dd($data);
     $this->count -= $data;
}



    public function render()
    {
        return view('livewire.rever-test-page');
    }
}
