<?php
use App\Http\Controllers\PostController;

    Route::prefix('posts')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[PostController::class,'index'])->middleware(['checkAction:all,view-post']);
        Route::post('/',[PostController::class,'store'])->middleware(['checkAction:all,create-post']);
        Route::get('/{id}',[PostController::class,'show'])->middleware(['checkAction:all,view-post']);
        Route::put('/{id}',[PostController::class,'update'])->middleware(['checkAction:all,update-post']);
        Route::delete('/{id}',[PostController::class,'destroy'])->middleware(['checkAction:all,delete-post']);
    });
    Route::get('/load-posts-by-view',[PostController::class,'loadByViews'])->middleware(['auth:sanctum' ,'checkAction:all,view-post']);
