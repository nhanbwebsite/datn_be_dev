<?php

use App\Http\Controllers\FileController;

Route::controller(FileController::class)->middleware('auth:sanctum')->prefix('files')->group(function(){
    Route::get('/', 'index')->middleware('checkAction::all,view-file');
    Route::get('/{id}', 'show')->middleware('checkAction::all,view-file');
    Route::post('/', 'store')->middleware('checkAction::all,view-file');
    Route::delete('/{id}', 'destroy')->middleware('checkAction::all,view-file');
});

?>
