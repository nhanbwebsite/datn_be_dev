<?php

use App\Http\Controllers\VNPayController;

Route::controller(VNPayController::class)->middleware('auth:sanctum')->prefix('vnpay')->group(function(){
    Route::post('/create', 'create');
});
Route::get('/vnpay/returnData', [VNPayController::class, 'returnData']);

?>
