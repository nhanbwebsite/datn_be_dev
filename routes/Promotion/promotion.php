<?php

use App\Http\Controllers\PromotionController;

Route::controller(PromotionController::class)->middleware('auth:sanctum')->prefix('promotions')->group(function(){
    Route::get('/', 'index')->middleware('checkAction::all,view-promotion');
    Route::get('/{id}', 'show')->middleware('checkAction::all,view-promotion');
    Route::post('/', 'store')->middleware('checkAction::all,create-promotion');
    Route::put('/{id}', 'update')->middleware('checkAction::all,update-promotion');
    Route::delete('/{id}', 'destroy')->middleware('checkAction::all,delete-promotion');
});

?>
