<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LikeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EmailCodeController;

Route::get('/email/verify', [EmailVerificationController::class, 'showEmailVerificationNotice'])
    ->name('verification.notice');
Route::post('/email/verification-notification', [EmailCodeController::class, 'resend'])
    ->name('verification.send');


Route::get('/verify-code', [EmailCodeController::class, 'showForm'])->name('verification.code.form');
Route::post('/verify-code', [EmailCodeController::class, 'verifyCode'])->name('verification.code.submit');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'detailShow'])->name('items.detail');

Route::get('/items', [ItemController::class, 'index'])->middleware(['auth', 'verified'])->name('items.list');

Route::middleware('auth')->group(function () {
    Route::post('/item/{item_id}/comment', [ItemController::class, 'postComment']);
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('like.toggle');
    Route::get('/sell', [ItemController::class, 'show'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    Route::get('/mypage', [ProfileController::class, 'show']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/purchase/{item_id}', [OrderController::class, 'index'])->name('purchase.show');
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'edit'])->name('purchase.edit');
    Route::patch('/purchase/address/{item_id}', [OrderController::class, 'update'])->name('purchase.address.update');
    Route::post('/purchase/confirm/{item_id}', [OrderController::class, 'confirm']);
    Route::get('/purchase/success/{item_id}', [OrderController::class, 'success']);
    Route::get('/purchase/cancel/{item_id}', [OrderController::class, 'cancel']);
});