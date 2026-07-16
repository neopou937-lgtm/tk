<?php

use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/admin/dashboard')->name('dashboard');
});

Route::post('midtrans/notification', MidtransWebhookController::class)
    ->name('midtrans.notification');

require __DIR__.'/admin.php';
require __DIR__.'/settings.php';
