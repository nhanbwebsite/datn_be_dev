<?php
use App\Http\Controllers\ContactController;

    Route::prefix('contact')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[ContactController::class,'index']);
        Route::post('/',[ContactController::class,'store']);
        Route::get('/{id}',[ContactController::class,'show']);
        Route::put('/{id}',[ContactController::class,'update']);
        Route::delete('/{id}',[ContactController::class,'destroy']);

    });
