<?php
    use App\Http\Controllers\specifications;

    Route::controller(specifications::class)->middleware(['auth:sanctum'])->prefix('specifications')->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

