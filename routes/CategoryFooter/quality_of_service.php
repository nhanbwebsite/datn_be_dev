<?php
use App\Http\Controllers\QualityOfServiceController;

    Route::prefix('quailityService')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[QualityOfServiceController::class,'index'])->middleware(['checkAction:all,view-quailityService']);
        Route::post('/',[QualityOfServiceController::class,'store'])->middleware(['checkAction:all,create-quailityService']);
        Route::get('/{id}',[QualityOfServiceController::class,'show'])->middleware(['checkAction:all,view-quailityService']);
        Route::put('/{id}',[QualityOfServiceController::class,'update'])->middleware(['checkAction:all,update-quailityService']);
        Route::delete('/{id}',[QualityOfServiceController::class,'destroy'])->middleware(['checkAction:all,delete-quailityService']);
    });
