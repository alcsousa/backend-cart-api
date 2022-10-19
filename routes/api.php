<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\RegisterUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegisterUserController::class, 'register']);

Route::post('/tokens', [TokenController::class, 'issueToken']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);

    Route::post('/carts', [CartController::class, 'createCart']);
    Route::post('/carts/{uuid}', [CartController::class, 'addItem']);
    Route::get('/carts/{uuid}', [CartController::class, 'viewCart']);
    Route::post('/carts/{uuid}/checkout', [CheckoutController::class, 'checkout']);
});
