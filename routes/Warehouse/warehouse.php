<?php

use App\Http\Controllers\WarehouseController;

Route::controller(WarehouseController::class)->prefix('warehouses')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
});

?>
