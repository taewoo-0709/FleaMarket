<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/webhook/stripe', [WebhookController::class, 'handle'])->name('stripe.webhook');