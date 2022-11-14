<?php

use App\Http\Controllers\SpecificationController;

Route::controller(SpecificationController::class)->middleware('auth:sanctum')->prefix('specifications')->group(function (){
    Route::get('/', 'index')->middleware('checkAction:all,view-specification');
    Route::post('/', 'store')->middleware('checkAction:all,create-specification');
    Route::get('/{id}', 'show')->middleware('checkAction:all,view-specification');
    Route::put('/{id}', 'update')->middleware('checkAction:all,update-specification');
    Route::delete('/{id}', 'destroy')->middleware('checkAction:all,delete-specification');
});

?>
