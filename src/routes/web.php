<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LikeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\EmailVerificationController;


Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/email/verify', [EmailVerificationController::class, 'showEmailVerificationNotice'])
    ->middleware(['auth'])
    ->name('verification.notice');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back();
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/', [ItemController::class, 'index'])
    ->middleware('verified.strict');
Route::get('/item/{item_id}', [ItemController::class, 'detailShow'])->name('items.detail');

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

    Route::get('/items', [ItemController::class, 'index'])->name('items.list');
    Route::get('/mypage/purchased', [ProfileController::class, 'show'])
    ->name('profile.purchased')
    ->defaults('page', 'buy');
});