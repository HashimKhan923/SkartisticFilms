<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');

Route::prefix('sk-admin')->name('admin.')->group(function () {

    Route::get('/login',  [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {

        Route::get('/panel', [AdminController::class, 'panel'])->name('panel');

        // Settings
        Route::post('/settings',      [AdminController::class, 'saveSettings'])->name('settings.save');

        // Timer
        Route::post('/timer',         [AdminController::class, 'saveTimerSettings'])->name('timer.save');

        // Movies
        Route::post('/movies',                [AdminController::class, 'storeMovie'])->name('movies.store');
        Route::post('/movies/{movie}',         [AdminController::class, 'updateMovie'])->name('movies.update');
        Route::delete('/movies/{movie}',       [AdminController::class, 'deleteMovie'])->name('movies.delete');

        // Characters
        Route::post('/characters',               [AdminController::class, 'storeCharacter'])->name('characters.store');
        Route::post('/characters/{character}',    [AdminController::class, 'updateCharacter'])->name('characters.update');
        Route::delete('/characters/{character}',  [AdminController::class, 'deleteCharacter'])->name('characters.delete');

        // Banners
        Route::post('/banners',              [AdminController::class, 'storeBanner'])->name('banners.store');
        Route::post('/banners/{banner}',      [AdminController::class, 'updateBanner'])->name('banners.update');
        Route::delete('/banners/{banner}',    [AdminController::class, 'deleteBanner'])->name('banners.delete');
    });
});