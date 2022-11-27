<?php

use App\Http\Controllers\FileController;

Route::controller(FileController::class)->middleware('auth:sanctum')->prefix('files')->group(function(){
    Route::get('/', 'index')->middleware('checkAction::all,view-file');
    Route::post('/', 'store')->middleware('checkAction::all,create-file');
    Route::delete('/{id}', 'destroy')->middleware('checkAction::all,delete-file');
});
Route::get('view/{id}', [FileController::class, 'show']);

?>
