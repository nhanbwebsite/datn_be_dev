<?php

use App\Http\Controllers\OrderController;

Route::controller(OrderController::class)->middleware('auth:sanctum')->prefix('orders')->group(function (){
    Route::get('/', 'index')->middleware('checkAction:all,view-order');
    Route::post('/', 'store')->middleware('checkAction:all,create-order');
    Route::get('/{id}', 'show')->middleware('checkAction:all,view-order');
    Route::put('/{id}', 'update')->middleware('checkAction:all,update-order');
    Route::delete('/{id}', 'destroy')->middleware('checkAction:all');
});

?>
