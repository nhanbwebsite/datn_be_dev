<?php

use App\Http\Controllers\PaymentMethodController;

Route::controller(PaymentMethodController::class)->prefix('payment-methods')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
