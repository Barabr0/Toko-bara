<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
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
