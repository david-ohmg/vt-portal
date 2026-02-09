<?php

use App\Http\Controllers\AuthController;
use App\Mail\PortalMail;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::livewire('/portal/batches', 'pages::portal.⚡batches')->name('portal.batches');
    Route::livewire('/portal/upload', 'pages::portal.⚡upload')->name('portal.upload');
    Route::livewire('/portal/profile', 'pages::portal.⚡profile')->name('portal.profile');
    Route::livewire('/portal/files', 'pages::portal.⚡files')->name('portal.files');
    Route::livewire('/portal/mail', 'pages::portal.⚡mail')->name('portal.mail');
    Route::livewire('portal/archive', 'pages::portal.⚡archive')->name('portal.archive');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/test-s3', function () {
        try {
            // Try to list bucket contents
            $files = Storage::disk('s3')->files();
            return 'S3 connected! Files: ' . count($files);
        } catch (\Exception $e) {
            return 'S3 Error: ' . $e->getMessage();
        }
    });
});

Route::get('/', fn () => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::fallback(fn () => redirect()->route('login'));
