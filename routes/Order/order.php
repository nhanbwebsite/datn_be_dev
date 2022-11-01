<?php

use App\Http\Controllers\OrderController;

Route::controller(OrderController::class)->middleware('auth:sanctum')->prefix('orders')->group(function (){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
});

?>
