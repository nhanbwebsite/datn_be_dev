<?php

use App\Http\Controllers\VNPayController;

Route::controller(VNPayController::class)->prefix('vnpay')->group(function(){
    Route::post('/create', 'create');
    Route::get('/returnData', 'returnData');
});
// Route::get('/vnpay/returnData', [VNPayController::class, 'returnData']);
?>
