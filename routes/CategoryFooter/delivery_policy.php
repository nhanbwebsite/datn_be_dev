<?php
use App\Http\Controllers\DeliveryPolicyController;

    Route::prefix('deliveryPolicy')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[DeliveryPolicyController::class,'index'])->middleware(['checkAction:all,view-deliveryPolicy']);
        Route::post('/',[DeliveryPolicyController::class,'store'])->middleware(['checkAction:all,create-deliveryPolicy']);
        Route::get('/{id}',[DeliveryPolicyController::class,'show'])->middleware(['checkAction:all,view-deliveryPolicy']);
        Route::put('/{id}',[DeliveryPolicyController::class,'update'])->middleware(['checkAction:all,update-deliveryPolicy']);
        Route::delete('/{id}',[DeliveryPolicyController::class,'destroy'])->middleware(['checkAction:all,delete-deliveryPolicy']);
    });
