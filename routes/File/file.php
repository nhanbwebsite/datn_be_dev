<?php

use App\Http\Controllers\FileController;

Route::controller(FileController::class)->middleware('auth:sanctum')->prefix('files')->group(function(){
    Route::post('/', 'store');
    Route::delete('/{id}', 'destroy');
});

Route::get('files/', [FileController::class, 'index']);
Route::get('files/{id}', [FileController::class, 'show']);

?>
