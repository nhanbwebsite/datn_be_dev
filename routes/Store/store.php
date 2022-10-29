<?php
    use App\Http\Controllers\StoreController;

    Route::prefix('stores')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[StoreController::class,'index']);
        Route::post('/',[StoreController::class,'store']);
        Route::get('/{id}',[StoreController::class,'show']);
        Route::put('/{id}',[StoreController::class,'update']);
        Route::delete('/{id}',[StoreController::class,'destroy']);
    });
