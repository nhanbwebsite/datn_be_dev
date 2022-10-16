<?php
    use App\Http\Controllers\StoreController;

    Route::prefix('admin')->group(function (){
        Route::get('/v1/stores',[StoreController::class,'index']);
        Route::post('/v1/stores',[StoreController::class,'store']);
        Route::get('/v1/stores/{id}',[StoreController::class,'show']);
        Route::patch('/v1/stores/{id}',[StoreController::class,'update']);
        Route::delete('/v1/stores/{id}',[StoreController::class,'destroy']);
    });
