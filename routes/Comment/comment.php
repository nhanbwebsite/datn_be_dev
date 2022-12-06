<?php

use App\Http\Controllers\CommentController;

    Route::controller(CommentController::class)->middleware(['auth:sanctum'])->prefix('comments')->group(function (){
        Route::get('/', 'index')->middleware(['checkAction:all,view-comment']);
        Route::post('/', 'store');
        Route::get('/{id}', 'show')->middleware(['checkAction:all,view-comment']);
        Route::put('/{id}', 'update')->middleware(['checkAction:all,update-comment']);
        Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-comment']);
    });
    Route::get('/commentsAll', [CommentController::class,'index'])->middleware(['checkAction:all,view-comment']);
?>
