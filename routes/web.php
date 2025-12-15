<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Producer\Dashboard;
use App\Livewire\Producer\ManageProducts;
use App\Livewire\Producer\Orders;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalogue', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produits/{product:slug}', [CatalogController::class, 'show'])->name('products.show');

// Route du tableau de bord
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/panier', function () {
    return view('cart');
})->name('cart');

Route::prefix('paiement')->name('payment.')->group(function () {
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/producer/products', ManageProducts::class)
         ->name('producer.products');
});

// Routes pour les commandes des utilisateurs
Route::middleware(['auth'])->prefix('commandes')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::put('/{order}/annuler', [OrderController::class, 'cancel'])->name('cancel');
    
    // Routes pour l'administration des commandes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/liste', [OrderController::class, 'allOrders'])->name('admin.index');
        Route::put('/admin/{order}', [OrderController::class, 'update'])->name('admin.update');
    });
});

// Routes pour les producteurs
Route::middleware(['auth'])->prefix('producer')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('producer.dashboard');
    Route::get('/products', ManageProducts::class)->name('producer.products');
    Route::get('/orders', Orders::class)->name('producer.orders');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mes-commandes', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/mes-commandes/{order}', [ProfileController::class, 'showOrder'])->name('profile.orders.show');
});

// Routes du panier
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::middleware(['auth'])->group(function () {
    // ... autres routes ...
    
    // Paiement
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/checkout/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/checkout/success', [PaymentController::class, 'success'])->name('payment.success');
});

// Product Management Routes (Livewire)
Route::middleware(['auth'])->prefix('producer')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('producer.dashboard');
    
    // Products
    Route::get('/products', ManageProducts::class)->name('producer.products');
    Route::get('/products/create', [\App\Http\Controllers\Producer\ProductController::class, 'create'])->name('producer.products.create');
    Route::post('/products', [\App\Http\Controllers\Producer\ProductController::class, 'store'])->name('producer.products.store');
    
    Route::get('/orders', Orders::class)->name('producer.orders');
});

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products', [\App\Http\Controllers\Producer\ProductController::class, 'store'])
    ->name('producer.products.store')
    ->middleware(['auth']);

require __DIR__.'/auth.php';
