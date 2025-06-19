<?php

use App\Livewire\TestPage;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Queues;
use App\Livewire\Admin\Branches;
use App\Livewire\Admin\Counters;
use App\Livewire\Admin\Services;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ListSettings;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Monitor\DisplayPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter\SelectCounter;
use App\Livewire\Counter\CounterTransactionPage;

Route::get('/', function () {
    return view('welcome');
})->name('home');


// ✅ Main dashboard redirect
Route::get('dashboard', function() {
    switch (Auth::user()->role) {
        case 'superadmin':
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'staff':
            return redirect()->route('counter.transaction');
    }
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
    Route::get('branch-settings', ListSettings::class)->name('admin.branch-settings');
    Route::get('settings/{branch}', Settings::class)->name('admin.settings')->where('branch', '[0-9]+');
    Route::get('settings/{branch}', Settings::class)->name('admin.settings')->where('branch', '[0-9]+');

});
    // Admin Routes
Route::middleware(['auth', 'verified', 'can:staff'])->prefix('counter')->group(function () {
    Route::get('select', SelectCounter::class)->name('counter.select');
    Route::get('transaction', CounterTransactionPage::class)->middleware(['counter.assigned'])->name('counter.transaction');
});

//display
Route::get('/display/{monitor}', DisplayPage::class)->name('display.show');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');
//     Route::get('settings/profile', Profile::class)->name('settings.profile');
//     Route::get('settings/password', Password::class)->name('settings.password');
//     Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
// });

require __DIR__.'/auth.php';
