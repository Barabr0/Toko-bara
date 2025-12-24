<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/product/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

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

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->name('dashboard');
});
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

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google','redirect')
    ->name('auth.google');
    Route::get('/auth/google/callback','callback')
    ->name('auth.google.callback');
});

Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.destroy');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
use App\Services\MidtransService;
Route::get('/debug-midtrans', function () {
    $config = [
        'merchant_id'   => config('midtrans.merchant_id'),
        'client_key'    => config('midtrans.client_key'),
        'server_key'    => config('midtrans.server_key') ? '***SET***' : 'NOT SET',
        'is_production' => config('midtrans.is_production'),
    ];

    try {
        $service = new MidtransService();

        $dummyOrder = new \App\Models\Order();
        $dummyOrder->order_number = 'TEST-' . time();
        $dummyOrder->total_amount = 10000;
        $dummyOrder->shipping_cost = 0;
        $dummyOrder->shipping_name = 'Test User';
        $dummyOrder->shipping_phone = '08123456789';
        $dummyOrder->shipping_address = 'Jl. Test No. 123';
        $dummyOrder->user = (object) [
            'name'  => 'Tester',
            'email' => 'test@example.com',
            'phone' => '08123456789',
        ];
        $dummyOrder->items = collect([
            (object) [
                'product_id'   => 1,
                'product_name' => 'Produk Test',
                'price'        => 10000,
                'quantity'     => 1,
            ],
        ]);

        $token = $service->createSnapToken($dummyOrder);

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Berhasil terhubung ke Midtrans!',
            'config'  => $config,
            'token'   => $token,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'ERROR',
            'message' => $e->getMessage(),
            'config'  => $config,
        ], 500);
    }
});
