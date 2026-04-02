<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\GeminiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.index');
});

// Admin Dashboard
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Auth Routes
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/setups', [DashboardController::class, 'setups'])->name('setups');
        Route::post('/setups', [DashboardController::class, 'storeSetup'])->name('setups.store');
        Route::put('/setups/{id}', [DashboardController::class, 'updateSetup'])->name('setups.update');
        Route::delete('/setups/{id}', [DashboardController::class, 'deleteSetup'])->name('setups.delete');

        Route::get('/punchlines', [DashboardController::class, 'punchlines'])->name('punchlines');
        Route::delete('/punchlines/{id}', [DashboardController::class, 'deletePunchline'])->name('punchlines.delete');

        Route::get('/users', [DashboardController::class, 'users'])->name('users');
        Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('users.delete');

        Route::delete('/comments/{id}', [DashboardController::class, 'deleteComment'])->name('comments.delete');

        Route::post('/gemini/generate', [GeminiController::class, 'generate'])->name('gemini.generate');
    });
});
