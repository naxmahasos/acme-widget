<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'add']);
    Route::post('/clear', [CartController::class, 'clear']);
    Route::get('/total', [CartController::class, 'total']);
});

Route::get('/', function () {
    return view('welcome');
});
