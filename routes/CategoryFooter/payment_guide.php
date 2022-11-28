<?php
use App\Http\Controllers\PaymentGuideController;

    Route::prefix('paymentGuide')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[PaymentGuideController::class,'index'])->middleware(['checkAction:all,view-paymentGuide']);
        Route::post('/',[PaymentGuideController::class,'store'])->middleware(['checkAction:all,create-paymentGuide']);
        Route::get('/{id}',[PaymentGuideController::class,'show'])->middleware(['checkAction:all,view-paymentGuide']);
        Route::put('/{id}',[PaymentGuideController::class,'update'])->middleware(['checkAction:all,update-paymentGuide']);
        Route::delete('/{id}',[PaymentGuideController::class,'destroy'])->middleware(['checkAction:all,delete-paymentGuide']);
    });
