<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback']);

// Auth
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/profile', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/setups', [SetupController::class, 'store'])->middleware('auth:sanctum');
Route::post('/setups/{id}/punchlines', [SetupController::class, 'storePunchline'])->middleware('auth:sanctum');
Route::get('/setups/{slug}', [SetupController::class, 'showBySlug']);
Route::get('/feed', [FeedController::class, 'feed']);
Route::get('/setups/{id}/punchlines', [FeedController::class, 'punchlines']);
// Route::post('/setups/{id}/punchlines', [FeedController::class, 'storePunchline'])->middleware('auth:sanctum');
Route::post('/punchlines/{id}/view', [FeedController::class, 'view']);
Route::post('/punchlines/{id}/laugh', [FeedController::class, 'laugh']);

Route::get('/punchlines/{id}/comments', [CommentController::class, 'index']);
Route::post('/punchlines/{id}/comments', [CommentController::class, 'store'])->middleware('auth:sanctum');
