<?php

use App\Events\NewQue;
use App\Livewire\TestPage;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Queues;
use App\Livewire\ReverTestPage;
use App\Livewire\Admin\Branches;
use App\Livewire\Admin\Counters;
use App\Livewire\Admin\Services;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Monitor\Monitors;
use App\Livewire\Admin\ListSettings;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Monitor\DisplayPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter\SelectCounter;
use App\Livewire\Admin\BranchQueueSettings;
use App\Livewire\Counter\CounterTransactionPage;
use App\Livewire\Monitor\BranchesListForMonitorMangement;

Route::get('/', function () {
    return view('welcome');
})->name('home');



// ✅ Main dashboard redirect
Route::get('dashboard', function() {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    switch (Auth::user()->role) {
        case 'superadmin':
        case 'admin':
            return redirect()->route('admin.branches');
        case 'staff':
            return redirect()->route('counter.transaction');
    }
    return redirect()->route('admin.branches');
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
    Route::get('settings/{branch}', Settings::class)->name('admin.settings');

    Route::get('branches-for-monitor-management', BranchesListForMonitorMangement::class)->name('admin.branches-for-monitor-management');
    Route::get('monitors/{branch}', Monitors::class)->name('admin.monitors');
    Route::get('branch-queue-settings/{branch}', BranchQueueSettings::class)->name('admin.branch-queue-settings');

});
    // Admin Routes
Route::middleware(['auth', 'verified', 'can:staff'])->prefix('counter')->group(function () {
    Route::get('select', SelectCounter::class)->name('counter.select');
    Route::get('transaction', CounterTransactionPage::class)->middleware(['counter.assigned'])->name('counter.transaction');
});

//display
Route::get('/display/{monitor}', DisplayPage::class)->name('display.show');

// Simple test route for generating queue tickets
Route::get('/create-test-queue/{branch?}/{service?}', function($branchId = null, $serviceId = null) {
    // Get the first branch or the one specified
    $branch = $branchId ? \App\Models\Branch::find($branchId) : \App\Models\Branch::first();

    if (!$branch) {
        return response()->json(['error' => 'Branch not found'], 404);
    }

    // Get the first service for this branch or the one specified
    $service = $serviceId ? \App\Models\Service::find($serviceId) : \App\Models\Service::where('branch_id', $branch->id)->first();

    if (!$service) {
        return response()->json(['error' => 'Service not found'], 404);
    }

    // Get the branch settings
    $setting = \App\Models\Setting::where('branch_id', $branch->id)->first();
    $base = $setting ? $setting->queue_number_base : 1;
    $prefix = $setting ? $setting->ticket_prefix : 'QUE';

    // Count today's issued tickets for this branch
    $todayCount = \App\Models\Queue::where('branch_id', $branch->id)
        ->whereDate('created_at', today())
        ->count();

    // Calculate next number
    $nextNumber = $base + $todayCount;

    // Format ticket number with prefix
    $formattedTicketNumber = $prefix . $nextNumber;

    // Create the queue
    $queue = \App\Models\Queue::create([
        'branch_id' => $branch->id,
        'service_id' => $service->id,
        'number' => $nextNumber,
        'ticket_number' => $formattedTicketNumber,
        'status' => 'waiting',
    ]);

    event(new NewQue($queue));

    return response()->json([
        'success' => true,
        'message' => 'Test queue created successfully',
        'queue' => [
            'id' => $queue->id,
            'branch' => $branch->name,
            'service' => $service->name,
            'number' => $queue->number,
            'ticket_number' => $queue->ticket_number,
            'created_at' => $queue->created_at->format('Y-m-d H:i:s'),
            'status' => $queue->status
        ],
    ]);
})->name('test.create-queue');
//     Route::redirect('settings', 'settings/profile');
//     Route::get('settings/profile', Profile::class)->name('settings.profile');
//     Route::get('settings/password', Password::class)->name('settings.password');
//     Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
// });

//reverb test page
Route::get('reverb-test', ReverTestPage::class)->name('reverb-test');

require __DIR__.'/auth.php';


