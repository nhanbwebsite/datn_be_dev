<?php

use App\Http\Controllers\CouponController;

Route::controller(CouponController::class)->middleware('auth:sanctum')->prefix('coupons')->group(function(){
    Route::get('/', 'index')->middleware('checkAction:all,view-coupon');
    Route::get('/{id}', 'show')->middleware('checkAction:all,view-coupon');
    Route::post('/', 'store')->middleware('checkAction:all,create-coupon');
    Route::put('/{id}', 'update')->middleware('checkAction:all,update-coupon');
    Route::delete('/{id}', 'destroy')->middleware('checkAction:all,delete-coupon');
});

?>
