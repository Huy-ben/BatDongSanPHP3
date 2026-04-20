<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

});

Route::get('home', [HomeController::class, 'data'])->name('homeApi');
Route::post('contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('category', [CategoryController::class, 'index'])->name('categoryApi');
Route::get('blog', [BlogController::class, 'index'])->name('blogApi');
Route::get('blog/{blog:slug}', [BlogController::class, 'show'])->name('blogDetailApi');
Route::get('posts/sell', [PostController::class, 'sell'])->name('postSellApi');
Route::get('posts/rent', [PostController::class, 'rent'])->name('postRentApi');
Route::get('locations/provinces', [LocationController::class, 'provinces'])->name('provincesApi');