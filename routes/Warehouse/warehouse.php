<?php

use App\Http\Controllers\WarehouseController;

Route::controller(WarehouseController::class)->middleware(['auth:sanctum'])->prefix('warehouses')->group(function(){
    Route::get('/', 'index')->middleware('checkAction:all,view-warehouse');
    Route::post('/', 'store')->middleware('checkAction:all,create-warehouse');
    Route::get('/{id}', 'show')->middleware('checkAction:all,view-warehouse');
    Route::put('/{id}', 'update')->middleware('checkAction:all,update-warehouse');
    Route::delete('/{id}', 'destroy')->middleware('checkAction:all,delete-warehouse');
});

?>
