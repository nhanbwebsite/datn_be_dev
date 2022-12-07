<?php

use App\Http\Controllers\CommentController;

    Route::controller(CommentController::class)->middleware(['auth:sanctum'])->prefix('comments')->group(function (){
        Route::get('/', 'index')->middleware(['checkAction:all,view-comment']);
        Route::post('/', 'store');
        Route::get('/{id}', 'show')->middleware(['checkAction:all,view-comment']);
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-comment']);
    });
    Route::get('/commentsAll', [CommentController::class,'index']);
    Route::get('/commentsAllByIdproduct/{id}', [CommentController::class,'getAllCommentByProductId']);
?>
