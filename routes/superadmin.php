<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\SuperAdmin\Dashboard;
use App\Livewire\SuperAdmin\ManageBranch;
use App\Livewire\SuperAdmin\ManageUser;
use App\Livewire\SuperAdmin\ViewBranchDetails;

Route::group(['middleware' => ['auth', 'verified', 'can:superadmin']], function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/branches', ManageBranch::class)->name('manage-branch');
    Route::get('/branch-details/{branch}', ViewBranchDetails::class)->name('branch-details');
    Route::get('/users', ManageUser::class)->name('manage-user');
    
});
