<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;

Route::get('/cart', [CartController::class, 'view']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::put('/cart/update/{id}', [CartController::class, 'update']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);

Route::post('/login', [AuthController::class, 'login']);
