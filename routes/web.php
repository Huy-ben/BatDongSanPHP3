<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Auth\GoogleLoginController;

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
Route::inertia('blog', 'Client/Blog')->name('blog');
Route::inertia('blog-detail', 'Client/BlogDetail')->name('blog-detail');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('api/contact', [ContactController::class, 'send'])->name('contact.send');
Route::inertia('profile', 'Client/Profile')->name('profile');
Route::get('api/category', [App\Http\Controllers\Api\CategoryController::class, 'index'])->name('categoryApi');
Route::get('api/blog', [App\Http\Controllers\Api\BlogController::class, 'index'])->name('blogApi');
Route::get('api/blog/{blog}', [App\Http\Controllers\Api\BlogController::class, 'show'])->name('blogDetailApi');
Route::get('api/home', [App\Http\Controllers\Api\HomeController::class, 'data'])->name('homeApi');
Route::post('auth/google', GoogleLoginController::class)->middleware('guest')->name('auth.google');
require __DIR__.'/settings.php';

