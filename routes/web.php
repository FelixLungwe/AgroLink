<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Producer\Dashboard;
use App\Livewire\Producer\ManageProducts;
use App\Livewire\Producer\Orders;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalogue', function () {
    return view('catalogue');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/producer/products', ManageProducts::class)
         ->name('producer.products');
});

Route::middleware(['auth'])->prefix('producer')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('producer.dashboard');
    Route::get('/products', ManageProducts::class)->name('producer.products');
    Route::get('/orders', Orders::class)->name('producer.orders');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
