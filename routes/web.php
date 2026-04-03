<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Route::inertia('/', 'Welcome', [
//     'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('category', 'Admin/Category')->name('category');
});

// Home
Route::inertia('/', 'Client/Home')->name('home');
// Post
Route::inertia('post-sell', 'Client/PostSell')->name('post-sell');
Route::inertia('post-rent', 'Client/PostRent')->name('post-rent');
Route::inertia('post-detail', 'Client/PostDetail')->name('post-detail');
Route::inertia('package', 'Client/Package')->name('package');
Route::get('api/category', [App\Http\Controllers\Api\CategoryController::class, 'index'])->name('categoryApi');
Route::get('api/home', [App\Http\Controllers\Api\HomeController::class, 'data'])->name('homeApi');
require __DIR__.'/settings.php';

