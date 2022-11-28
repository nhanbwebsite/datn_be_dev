<?php
use App\Http\Controllers\StoreRulesController;

    Route::prefix('storeRules')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[StoreRulesController::class,'index'])->middleware(['checkAction:all,view-storeRules']);
        Route::post('/',[StoreRulesController::class,'store'])->middleware(['checkAction:all,create-storeRules']);
        Route::get('/{id}',[StoreRulesController::class,'show'])->middleware(['checkAction:all,view-storeRules']);
        Route::put('/{id}',[StoreRulesController::class,'update'])->middleware(['checkAction:all,update-storeRules']);
        Route::delete('/{id}',[StoreRulesController::class,'destroy'])->middleware(['checkAction:all,delete-storeRules']);
    });
