<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AttachCartToken;

Route::middleware([AttachCartToken::class])->group(function () {
    Route::get('/cart', [CartController::class, 'getCartItems']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/login/fake', [AuthController::class, 'fakeLogin'])->name('login.fake');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
