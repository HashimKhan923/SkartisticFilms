<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/page/{slug}', [PublicController::class, 'page'])->name('page');

Route::prefix('sk-admin')->name('admin.')->group(function () {

    Route::get('/login',  [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {

        Route::get('/panel', [AdminController::class, 'panel'])->name('panel');

        Route::post('/settings',      [AdminController::class, 'saveSettings'])->name('settings.save');
        Route::post('/timer',         [AdminController::class, 'saveTimerSettings'])->name('timer.save');

        Route::post('/movies',                [AdminController::class, 'storeMovie'])->name('movies.store');
        Route::post('/movies/{movie}',         [AdminController::class, 'updateMovie'])->name('movies.update');
        Route::delete('/movies/{movie}',       [AdminController::class, 'deleteMovie'])->name('movies.delete');

        Route::post('/characters',               [AdminController::class, 'storeCharacter'])->name('characters.store');
        Route::post('/characters/{character}',    [AdminController::class, 'updateCharacter'])->name('characters.update');
        Route::delete('/characters/{character}',  [AdminController::class, 'deleteCharacter'])->name('characters.delete');

        Route::post('/banners',              [AdminController::class, 'storeBanner'])->name('banners.store');
        Route::post('/banners/{banner}',      [AdminController::class, 'updateBanner'])->name('banners.update');
        Route::delete('/banners/{banner}',    [AdminController::class, 'deleteBanner'])->name('banners.delete');

        Route::post('/pages',           [AdminController::class, 'storePage'])->name('pages.store');
        Route::post('/pages/{page}',    [AdminController::class, 'updatePage'])->name('pages.update');
        Route::delete('/pages/{page}',  [AdminController::class, 'deletePage'])->name('pages.delete');

        Route::post('/reviews',              [AdminController::class, 'storeReview'])->name('reviews.store');
        Route::post('/reviews/{review}',     [AdminController::class, 'updateReview'])->name('reviews.update');
        Route::delete('/reviews/{review}',   [AdminController::class, 'deleteReview'])->name('reviews.delete');

        Route::post('/software',               [AdminController::class, 'storeSoftware'])->name('software.store');
        Route::post('/software/{software}',    [AdminController::class, 'updateSoftware'])->name('software.update');
        Route::delete('/software/{software}',  [AdminController::class, 'deleteSoftware'])->name('software.delete');
    });
});