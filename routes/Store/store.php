<?php
    use App\Http\Controllers\StoreController;

    Route::prefix('stores')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[StoreController::class,'index'])->middleware(['checkAction:all,view-store']);
        Route::post('/',[StoreController::class,'store'])->middleware(['checkAction:all,create-store']);
        Route::get('/{id}',[StoreController::class,'show'])->middleware(['checkAction:all,view-store']);
        Route::put('/{id}',[StoreController::class,'update'])->middleware(['checkAction:all,update-store']);
        Route::delete('/{id}',[StoreController::class,'destroy'])->middleware(['checkAction:all,delete-store']);
    });