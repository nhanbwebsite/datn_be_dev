<?php
use App\Http\Controllers\WarrantyController;

    Route::prefix('warranty')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[WarrantyController::class,'index'])->middleware(['checkAction:all,view-warranty']);
        Route::post('/',[WarrantyController::class,'store'])->middleware(['checkAction:all,create-warranty']);
        Route::get('/{id}',[WarrantyController::class,'show'])->middleware(['checkAction:all,view-warranty']);
        Route::put('/{id}',[WarrantyController::class,'update'])->middleware(['checkAction:all,update-warranty']);
        Route::delete('/{id}',[WarrantyController::class,'destroy'])->middleware(['checkAction:all,delete-warranty']);
    });
