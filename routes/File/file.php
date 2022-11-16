<?php

use App\Http\Controllers\FileController;

Route::controller(FileController::class)->middleware('auth:sanctum')->prefix('files')->group(function(){
    Route::post('/', 'store');
});

?>
