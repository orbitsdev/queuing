<?php

namespace App\Livewire\Counter;

use App\Models\Counter;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Title;

class SelectCounter extends Component
{
    use WireUiActions;

    public $search = '';

    #[Title('Select Counter')]
    public function getCountersProperty()
    {
        return Counter::currentBranch()
            ->with(['users', 'services'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('services', fn ($q) =>
                        $q->where('name', 'like', '%' . $this->search . '%')
                    );
            })
            ->orderBy('name')
            ->get();
    }

    public function mount()
    {
        if (auth()->user()->counter_id) {
            return redirect()->route('counter.transaction');
        }
    }

    public function assign($counterId)
    {
        $counter = Counter::findOrFail($counterId);

        // if ($counter->user_id) {
        //     return;
        // }

        $this->dialog()->confirm([
            'title' => 'Confirm Counter Selection',
            'description' => "Are you sure you want to use {$counter->name}?",
            'acceptLabel' => 'Yes, Use This Counter',
            'method' => 'confirmAssign',
            'params' => $counter,
        ]);
    }

    public function confirmAssign(Counter $counter)
    {
        // dd($counter->toArray());

        // if ($counter->user_id) {
        //     $this->dialog()->error(
        //         title: 'Counter Occupied',
        //         description: 'This counter was just taken by someone else. Please choose another.'
        //     );
        //     return;
        // }

        $user = auth()->user();
        $user->update(['counter_id' => $counter->id]);
        //since it is 1 to many the trancaiton will be change
        // $counter->update(['user_id' => $user->id]);

        $this->dialog()->success(
            title: 'Counter Assigned',
            description: "You are now using {$user->counter?->name}."
        );

        return redirect()->route('counter.transaction');
    }

    public function render()
    {
        return view('livewire.counter.select-counter', [
            'counters' => $this->counters,
        ]);
    }
}
