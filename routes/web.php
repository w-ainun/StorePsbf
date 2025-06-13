<?php

use Illuminate\Support\Facades\Route;
// Hapus: use App\Filament\Pages\CustomLoginPage; // <-- Hapus baris ini

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Hapus atau komentari seluruh blok rute ini
// Route::get('/loginpage', CustomLoginPage::class)
//     ->name('login')
//     ->middleware('guest');

// Rute default atau rute lain untuk aplikasi front-end
Route::get('/', function () {
    return view('welcome');
});

// Jika Anda memiliki rute dashboard /home untuk pembeli, tetap biarkan
Route::get('/home', function () {
    return "Selamat datang di dashboard Pembeli!";
})->middleware('auth');