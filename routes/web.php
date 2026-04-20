<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
Route::post('preferred-location', [HomeController::class, 'setPreferredLocation'])->name('home.preferred-location');
// Post
Route::inertia('post-sell', 'Client/PostSell')->name('post-sell');
Route::inertia('post-rent', 'Client/PostRent')->name('post-rent');
Route::get('post-detail/{postIdentifier?}', [PostController::class, 'detail'])->name('post-detail');
Route::middleware(['auth'])->group(function () {
    Route::get('dang-tin', [PostController::class, 'create'])->name('post-create');
    Route::post('dang-tin', [PostController::class, 'store'])->name('post-store');
    Route::get('sua-tin/{post}', [PostController::class, 'edit'])->name('post-edit');
    Route::patch('sua-tin/{post}', [PostController::class, 'update'])->name('post-update');
});
Route::get('package', PackageController::class)->name('package');
Route::inertia('thanh-toan', 'Client/Payment')->name('payment');
Route::get('thanh-toan/ket-qua', [PaymentController::class, 'result'])->name('payment.result');
Route::inertia('blog', 'Client/Blog')->name('blog');
Route::inertia('blog-detail/{blogIdentifier?}', 'Client/BlogDetail')->name('blog-detail');
Route::inertia('about-us', 'Client/AboutUs')->name('about-us');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::middleware(['auth'])->group(function () {
    Route::inertia('favorites', 'Client/Favorites')->name('favorites');
    Route::inertia('notifications', 'Client/Notification')->name('notifications');
    Route::get('notifications/data', [NotificationController::class, 'index'])->name('notifications.data');
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('favorites/data', [FavoriteController::class, 'index'])->name('favorites.data');
    Route::get('favorites/ids', [FavoriteController::class, 'ids'])->name('favorites.ids');
    Route::post('favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{post}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::delete('favorites', [FavoriteController::class, 'clear'])->name('favorites.clear');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('profile/lich-su-thanh-toan', [ProfileController::class, 'paymentHistory'])->name('profile.payment-history');
    Route::patch('profile', [ProfileController::class, 'update'])->name('client.profile.update');
    Route::post('profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('payment/trial', [PaymentController::class, 'activateTrial'])->name('payment.trial.activate');
    Route::post('payment/vnpay', [PaymentController::class, 'create'])->name('payment.vnpay.create');
    Route::post('payment/result/consume', [PaymentController::class, 'consumeResult'])->name('payment.result.consume');
});
Route::get('payment/vnpay/return', [PaymentController::class, 'callback'])->name('payment.vnpay.return');
Route::post('auth/google', GoogleLoginController::class)->middleware('guest')->name('auth.google');
require __DIR__.'/settings.php';