<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViewPackage\PackageController;
use App\Http\Controllers\ViewOrder\OrderController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[PackageController::class,'index'])->name('viewpackage.index');
Route::get('/viewpackage/{package}',[PackageController::class,'show'])->name('viewpackage.show');
// Route::post('/viewpackage/{package}/order',[PackageController::class,'store'])->name('viewpackage.store');

// Route::get('/checkout',[OrderController::class,'index'])->name('checkout.index');
// Route::post('/viewpackage/{package}/order',[OrderController::class,'store'])->name('order.store');

Route::post('/order/{id}', [OrderController::class, 'store'])
    ->where('id', '[0-9a-fA-F\-]+') // Validasi UUID format
    ->name('order.store');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';