<?php

use App\Http\Controllers\OrderStatusController;

Route::controller(OrderStatusController::class)->prefix('order-status')->group(function (){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
