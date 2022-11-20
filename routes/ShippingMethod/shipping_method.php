<?php

use App\Http\Controllers\ShippingMethodController;

Route::controller(ShippingMethodController::class)->middleware('auth:sanctum')->prefix('shipping-methods')->group(function (){
    Route::get('/', 'index')->middleware('checkAction:all,view-shipping-method');
    Route::post('/', 'store')->middleware('checkAction:all,create-shipping-method');
    Route::get('/{id}', 'show')->middleware('checkAction:all,view-shipping-method');
    Route::put('/{id}', 'update')->middleware('checkAction:all,update-shipping-method');
    Route::delete('/{id}', 'destroy')->middleware('checkAction:all,delete-shipping-method');
});

Route::get('/client/shipping-methods', [ShippingMethodController::class, 'getClientShippingMethods']);

?>
