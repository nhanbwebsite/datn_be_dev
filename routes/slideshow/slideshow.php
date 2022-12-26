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
Route::get('/showSlideBycate', [SlideshowController::class, 'showSlideBycate']);
Route::post('/active_slide_cate', [SlideshowController::class, 'showSlideBycateUpdate']);
Route::get('/listSlideshowByCate', [SlideshowController::class, 'listSlideshowByCate']);
Route::get('/listSlideshowMain', [SlideshowController::class, 'listSlideshowMain']);
Route::post('/updateSlideMain', [SlideshowController::class, 'updateSlideMain']);
Route::delete('/deleteSlideDetails/{id}', [SlideshowController::class, 'deleteSlideDetails']);
Route::get('/getSlideshow/{id}', [SlideshowController::class, 'listSlideshowByCateByID']);
