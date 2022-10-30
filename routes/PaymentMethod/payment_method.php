<?php

use App\Http\Controllers\PaymentMethodController;

Route::controller(PaymentMethodController::class)->middleware(['auth:sanctum'])->prefix('payment-methods')->group(function(){
    Route::get('/', 'index')->middleware(['checkAction:all,view-payment-method']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-payment-method']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-payment-method']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-payment-method']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-payment-method']);
});

?>
