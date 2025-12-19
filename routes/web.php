<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\CheckoutController;
// use App\Http\Controllers\WishlistController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
// use App\Http\Controllers\AdminProductController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/products', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::middleware('auth')->group(function () {
    // Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    // Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    // Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    // Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    // Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    // Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'admin'])
->prefix('admin')
->name('admin.')
->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->name('dashboard');
    Route::resource('/products', ProductController::class);
});
Route::get('/', [HomeController::class, 'index'])->name('home');


Auth::routes();
Route::get('/tentang', function () {
 return view('tentang');
});
Route::get('/sapa/{nama}', function ($nama) {
 return "halo, $nama selamat datang di Toko online";
});
Route::get('/kategori/{nama?}', function ($nama = 'Semua') {
 return "menampilkan semua kategori: $nama";
});
Route::get('/produk/{id}', function ($id) {
 return "Detail Produk #$id";
})->name('produk.detail');

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    });
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google','redirect')
        ->name('auth.google');
    Route::get('/auth/google/callback','callback')
        ->name('auth.google.callback');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
