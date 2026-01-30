<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::livewire('/portal/batches', 'pages::portal.⚡batches')->name('portal.batches');
    Route::livewire('/portal/upload', 'pages::portal.⚡upload')->name('portal.upload');
    Route::livewire('/portal/profile', 'pages::portal.⚡profile')->name('portal.profile');
    Route::livewire('/portal/files', 'pages::portal.⚡files')->name('portal.files');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/', fn () => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

