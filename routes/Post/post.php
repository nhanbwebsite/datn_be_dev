<?php
use App\Http\Controllers\PostController;

    Route::prefix('posts')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[PostController::class,'index']);
        Route::post('/',[PostController::class,'store']);
        Route::get('/{id}',[PostController::class,'show']);
        Route::put('/{id}',[PostController::class,'update']);
        Route::delete('/{id}',[PostController::class,'destroy']);
    });
    Route::get('/load-posts-by-view',[PostController::class,'loadByViews']);
