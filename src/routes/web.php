<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LikeController;


Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::patch('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/sell', [ItemController::class, 'show']);
    Route::post('/sell', [ItemController::class, 'store']);
    Route::post('/item/{item_id}/comment', [ItemController::class, 'postComment']);
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('like.toggle');
    Route::get('/purchase/{item_id}', [OrderController::class, 'index']);
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'edit']);
    Route::patch('/purchase/address/{item_id}', [OrderController::class, 'update']);
    Route::post('/purchase/confirm/{item_id}', [OrderController::class, 'confirm']);
    Route::get('/purchase/success/{item_id}', [OrderController::class, 'success']);
    Route::get('/purchase/cancel/{item_id}', [OrderController::class, 'cancel']);
    Route::post('/stripe/webhook', [OrderController::class, 'webhook']);

});

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'detailShow'])->name('items.detail');