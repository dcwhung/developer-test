<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;

Route::get('/forbidden', [UserController::class, 'forbidden'])->name('forbidden');
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/items', ItemController::class)->except(['index', 'show']);
    
    Route::prefix('orders')->middleware('auth')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::post('/place-order', [OrderController::class, 'placeOrder']);
        Route::post('/process-payment', [OrderController::class, 'processPayment']);
    });
});
Route::apiResource('/items', ItemController::class)->only(['index', 'show']);
