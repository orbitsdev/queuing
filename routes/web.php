<?php

use App\Livewire\TestPage;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Branches;
use App\Livewire\Admin\Services;
use App\Livewire\Admin\Counters;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Queues;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('dashboard', function(){
    return redirect()->route('admin.dashboard');
})->name('dashboard');

Route::get('test-page', TestPage::class)
    ->middleware(['auth', 'verified'])
    ->name('test-page');

    // Admin Routes
Route::middleware(['auth', 'verified', 'can:superadmin_or_admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('branches', Branches::class)->name('admin.branches');
    Route::get('services', Services::class)->name('admin.services');
    Route::get('counters', Counters::class)->name('admin.counters');
    Route::get('users', Users::class)->name('admin.users');
    Route::get('queues', Queues::class)->name('admin.queues');
});


// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');
//     Route::get('settings/profile', Profile::class)->name('settings.profile');
//     Route::get('settings/password', Password::class)->name('settings.password');
//     Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
// });

require __DIR__.'/auth.php';
