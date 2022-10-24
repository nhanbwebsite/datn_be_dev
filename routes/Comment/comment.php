<?php

use App\Http\Controllers\CommentController;

Route::controller(CommentController::class)->prefix('comments')->group(function (){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
})

?>
