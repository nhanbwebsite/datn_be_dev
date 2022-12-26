<?php

use App\Http\Controllers\WarehouseController;

Route::controller(WarehouseController::class)->middleware(['auth:sanctum'])->prefix('warehouses')->group(function(){

    Route::post('/', 'store')->middleware('checkAction:all,create-warehouse');

    Route::put('/{id}', 'update')->middleware('checkAction:all,update-warehouse');
    Route::delete('/{id}', 'destroy')->middleware('checkAction:all,delete-warehouse');
});
Route::get('/warehouses', [WarehouseController::class,'index']);
Route::get('warehouses/{id}', [WarehouseController::class,'show']);
Route::get('/getAllNoPaginate', [WarehouseController::class,'getAllNopaginate']);
?>
