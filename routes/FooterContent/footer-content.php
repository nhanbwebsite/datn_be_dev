<?php
use App\Http\Controllers\FooterContentController;

    Route::prefix('footer-content')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[FooterContentController::class,'index']);
        Route::post('/',[FooterContentController::class,'store']);
        Route::get('/{id}',[FooterContentController::class,'show']);
        Route::put('/{id}',[FooterContentController::class,'update']);
        Route::delete('/{id}',[FooterContentController::class,'destroy']);
    });
    Route::get('/client/footer-content/load-all',[FooterContentController::class,'loadAll']);
    Route::get('load-footer-content/{id}',[FooterContentController::class,'loadClient']);

