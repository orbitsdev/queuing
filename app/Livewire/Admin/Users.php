<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Users extends Component
{
    use WithPagination;

    #[Title('User Management')]
    
    public $showModal = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $selectedUserId;
    public $selectedBranch = null;
    public $showResetPassword = false;
    
    #[Rule('required|min:3|max:255')]
    public $name = '';
    
    #[Rule('required|email|max:255')]
    public $email = '';
    
    #[Rule('required|exists:branches,id')]
    public $branch_id = '';
    
    #[Rule('required|in:admin,staff,coordinator')]
    public $role = '';
    
    // Only required for new users or password reset
    public $password = '';
    public $password_confirmation = '';

    public function mount()
    {
        // Set default branch if none selected
        if (!$this->selectedBranch) {
            $this->selectedBranch = Branch::first()?->id;
        }
    }

    public function updatedSelectedBranch($value)
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'email', 'role', 'password', 'password_confirmation', 'isEditing', 'selectedUserId']);
        $this->branch_id = $this->selectedBranch;
        $this->showModal = true;
    }

    public function edit(User $user)
    {
        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->branch_id = $user->branch_id;
        $this->role = $user->role;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|min:3|max:255',
            'email' => $this->isEditing 
                ? 'required|email|max:255|unique:users,email,' . $this->selectedUserId
                : 'required|email|max:255|unique:users,email',
            'branch_id' => 'required|exists:branches,id',
            'role' => 'required|in:admin,staff,coordinator'
        ];

        if (!$this->isEditing) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'branch_id' => $this->branch_id,
            'role' => $this->role
        ];

        if (!$this->isEditing) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $user = User::find($this->selectedUserId);
            $user->update($data);
            
            $this->notification()->success(
                $title = 'Success',
                $description = 'User updated successfully'
            );
        } else {
            User::create($data);
            
            $this->notification()->success(
                $title = 'Success',
                $description = 'User created successfully'
            );
        }

        $this->reset(['showModal', 'name', 'email', 'role', 'password', 'password_confirmation', 'isEditing', 'selectedUserId']);
    }

    public function confirmDelete(User $user)
    {
        if ($user->id === auth()->guard()->id()) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'You cannot delete your own account'
            );
            return;
        }

        $this->selectedUserId = $user->id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $user = User::find($this->selectedUserId);
        
        if ($user->queues()->exists()) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'Cannot delete user with existing queues'
            );
            return;
        }

        $user->delete();
        $this->notification()->success(
            $title = 'Success',
            $description = 'User deleted successfully'
        );
        $this->reset(['confirmingDeletion', 'selectedUserId']);
    }

    public function showPasswordReset(User $user)
    {
        $this->selectedUserId = $user->id;
        $this->reset(['password', 'password_confirmation']);
        $this->showResetPassword = true;
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => ['required', 'confirmed', Password::defaults()]
        ]);

        $user = User::find($this->selectedUserId);
        $user->update([
            'password' => Hash::make($this->password)
        ]);

        $this->notification()->success(
            $title = 'Success',
            $description = 'Password reset successfully'
        );
        
        $this->reset(['showResetPassword', 'password', 'password_confirmation', 'selectedUserId']);
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'branches' => Branch::orderBy('name')->get(),
            'users' => User::when($this->selectedBranch, function($query) {
                    $query->where('branch_id', $this->selectedBranch);
                })
                ->withCount('queues')
                ->with('branch')
                ->latest()
                ->paginate(10)
        ]);
    }
}
