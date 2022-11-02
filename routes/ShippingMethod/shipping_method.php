<?php

use App\Http\Controllers\ShippingMethodController;

Route::controller(ShippingMethodController::class)->middleware('auth:sanctum')->prefix('shipping-methods')->group(function (){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
