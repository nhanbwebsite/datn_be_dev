<?php
use App\Http\Controllers\StoreIntroductionController;

    Route::prefix('storeIntro')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[StoreIntroductionController::class,'index'])->middleware(['checkAction:all,view-storeIntro']);
        Route::post('/',[StoreIntroductionController::class,'store'])->middleware(['checkAction:all,create-storeIntro']);
        Route::get('/{id}',[StoreIntroductionController::class,'show'])->middleware(['checkAction:all,view-storeIntro']);
        Route::put('/{id}',[StoreIntroductionController::class,'update'])->middleware(['checkAction:all,update-storeIntro']);
        Route::delete('/{id}',[StoreIntroductionController::class,'destroy'])->middleware(['checkAction:all,delete-storeIntro']);
    });
