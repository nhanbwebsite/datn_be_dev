<?php

use App\Http\Controllers\OrderStatusController;

Route::controller(OrderStatusController::class)->middleware(['auth:sanctum', 'checkAction:all,view-order-status,create-order-status,update-order-status,delete-order-status'])->prefix('order-status')->group(function (){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
