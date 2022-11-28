<?php
use App\Http\Controllers\TradingConditionController;

    Route::prefix('tradingCondition')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[TradingConditionController::class,'index'])->middleware(['checkAction:all,view-tradingCondition']);
        Route::post('/',[TradingConditionController::class,'store'])->middleware(['checkAction:all,create-tradingCondition']);
        Route::get('/{id}',[TradingConditionController::class,'show'])->middleware(['checkAction:all,view-tradingCondition']);
        Route::put('/{id}',[TradingConditionController::class,'update'])->middleware(['checkAction:all,update-tradingCondition']);
        Route::delete('/{id}',[TradingConditionController::class,'destroy'])->middleware(['checkAction:all,delete-tradingCondition']);
    });
