<?php

use App\Http\Controllers\PaymentMethodController;

Route::controller(PaymentMethodController::class)->middleware(['auth:sanctum', 'checkAction:all,view-payment-method,create-payment-method,update-payment-method,delete-payment-method'])->prefix('payment-methods')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
