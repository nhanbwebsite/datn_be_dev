<?php

use App\Http\Controllers\FileController;

Route::controller(FileController::class)->middleware('auth:sanctum')->prefix('files')->group(function(){
    Route::post('/', 'store');
    Route::delete('/{id}', 'destroy');
});

Route::post('/delete-many-files', [FileController::class, 'deleteFiles'])->middleware('auth:sanctum');
Route::get('files/', [FileController::class, 'index']);
Route::get('files/{id}', [FileController::class, 'show']);
Route::get('files/view/{fileName}', [FileController::class, 'viewFile']);

?>
