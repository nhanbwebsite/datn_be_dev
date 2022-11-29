<?php
use App\Http\Controllers\ShoppingGuideOnlineController;

    Route::prefix('shoppingGuide')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[ShoppingGuideOnlineController::class,'index'])->middleware(['checkAction:all,view-shoppingGuide']);
        Route::post('/',[ShoppingGuideOnlineController::class,'store'])->middleware(['checkAction:all,create-shoppingGuide']);
        Route::get('/{id}',[ShoppingGuideOnlineController::class,'show'])->middleware(['checkAction:all,view-shoppingGuide']);
        Route::put('/{id}',[ShoppingGuideOnlineController::class,'update'])->middleware(['checkAction:all,update-shoppingGuide']);
        Route::delete('/{id}',[ShoppingGuideOnlineController::class,'destroy'])->middleware(['checkAction:all,delete-shoppingGuide']);
    });
