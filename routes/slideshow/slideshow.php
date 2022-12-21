<?php

use App\Http\Controllers\SlideshowController;

Route::controller(SlideshowController::class)->middleware('auth:sanctum')->prefix('slideshow')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
Route::get('clients/slideshow/{id}', [SlideshowController::class, 'show']);
Route::get('/slideshowclient', [SlideshowController::class, 'getclientslideshowDetails']);
