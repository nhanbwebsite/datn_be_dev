<?php

use App\Http\Controllers\OrderStatusController;

Route::controller(OrderStatusController::class)->middleware(['auth:sanctum'])->prefix('order-status')->group(function (){
    Route::get('/', 'index')->middleware(['checkAction:all,view-order-status']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-order-status']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-order-status']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-order-status']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-order-status']);
});

?>
