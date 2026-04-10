<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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
Route::middleware(['auth'])->group(function () {
    Route::get('dang-tin', [PostController::class, 'create'])->name('post-create');
    Route::post('dang-tin', [PostController::class, 'store'])->name('post-store');
});
Route::inertia('package', 'Client/Package')->name('package');
Route::inertia('blog', 'Client/Blog')->name('blog');
Route::inertia('blog-detail', 'Client/BlogDetail')->name('blog-detail');
Route::inertia('about-us', 'Client/AboutUs')->name('about-us');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('api/contact', [ContactController::class, 'send'])->name('contact.send');
Route::middleware(['auth'])->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('profile', [ProfileController::class, 'update'])->name('client.profile.update');
    Route::post('profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
});
Route::get('api/category', [App\Http\Controllers\Api\CategoryController::class, 'index'])->name('categoryApi');
Route::get('api/blog', [App\Http\Controllers\Api\BlogController::class, 'index'])->name('blogApi');
Route::get('api/blog/{blog}', [App\Http\Controllers\Api\BlogController::class, 'show'])->name('blogDetailApi');
Route::get('api/home', [App\Http\Controllers\Api\HomeController::class, 'data'])->name('homeApi');
Route::post('auth/google', GoogleLoginController::class)->middleware('guest')->name('auth.google');
require __DIR__.'/settings.php';

