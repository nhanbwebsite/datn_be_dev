<?php
use App\Http\Controllers\LogoController;

    Route::prefix('logo')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[LogoController::class,'index']);
        Route::post('/',[LogoController::class,'store']);
        Route::get('/{id}',[LogoController::class,'show']);
        Route::put('/{id}',[LogoController::class,'update']);
        Route::delete('/{id}',[LogoController::class,'destroy']);
    });
    Route::get('client/logo/',[LogoController::class,'index']);
