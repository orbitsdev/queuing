<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KioskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Kiosk API Routes
Route::prefix('kiosk')->group(function () {
    Route::post('/check-branch', [KioskController::class, 'checkBranch']);
    Route::get('/branch/{code}', [KioskController::class, 'getBranch']);
    Route::get('/services/{branchCode}', [KioskController::class, 'getServices']);
    Route::post('/queue', [KioskController::class, 'createQueue']);
});
