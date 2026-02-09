<?php

namespace App\Livewire\Counter;

use App\Models\Counter;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::transaction(function () use ($counter) {
                // Lock and re-fetch counter to prevent race condition
                $counter = Counter::where('id', $counter->id)
                    ->lockForUpdate()
                    ->first();

                if (!$counter) {
                    throw new \Exception('Counter not found.');
                }

                // Check if counter is already occupied by another user
                if ($counter->user_id && $counter->user_id !== auth()->id()) {
                    throw new \Exception('This counter was just taken by someone else. Please choose another.');
                }

                $user = auth()->user();

                // Update both user and counter to maintain relationship
                $user->update(['counter_id' => $counter->id]);
                $counter->update(['user_id' => $user->id]);
            });

            $this->dialog()->success(
                title: 'Counter Assigned',
                description: "You are now using {$counter->name}."
            );

            return redirect()->route('counter.transaction');

        } catch (\Exception $e) {
            $this->dialog()->error(
                title: 'Counter Unavailable',
                description: $e->getMessage()
            );
            return;
        }
    }

    public function render()
    {
        return view('livewire.counter.select-counter', [
            'counters' => $this->counters,
        ]);
    }
}
