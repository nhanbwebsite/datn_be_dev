<?php
    use App\Http\Controllers\SlideshowController;

    Route::controller(SlideshowController::class)->middleware('auth:sanctum')->prefix('slideshow')->group(function (){
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::get('/slideshowclient');
