<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;


Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::patch('/mypage/profile', [ProfileController::class, 'update']);
});

Route::get('/', [ItemController::class, 'index']);
