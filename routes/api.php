<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewOrder\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/order/{id}', [OrderController::class, 'store'])
//     ->where('id', '[0-9a-fA-F\-]+') // Validasi UUID format
//     ->name('order.store');