<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Ubah {receiver} menjadi {token}
    Route::get('/cbt/{token}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/cbt/{token}', [ChatController::class, 'sendMessage'])->name('chat.send');
});

Route::get('/penjumlahan', [ChatController::class, 'contacts'])->name('chat.contacts');

Route::post('/latihan/unlock', [ChatController::class, 'unlockContacts'])->name('chat.unlock');
Route::get('/latihan/lock', [ChatController::class, 'lockContacts'])->name('chat.lock_session');

require __DIR__.'/auth.php';
