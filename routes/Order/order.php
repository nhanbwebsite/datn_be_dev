<?php

use App\Http\Controllers\OrderController;

Route::controller(OrderController::class)->middleware('auth:sanctum')->prefix('orders')->group(function (){
    Route::get('/', 'index')->middleware('checkAction:all,view-order');
    Route::post('/', 'store')->middleware('checkAction:all,create-order');
    Route::get('/{id}', 'show')->middleware('checkAction:all,view-order');
    Route::put('/{id}', 'update')->middleware('checkAction:all,update-order');
    // Route::delete('/{id}', 'destroy')->middleware('checkAction:all');
    Route::post('/approve-order/{code}', 'approveOrder')->middleware('checkAction:all,update-order');
    Route::get('/export-bill/{order_code}', 'exportOrdeBillr')->middleware('checkAction::all,view-order');
});
// Route::post('/approve-order', [OrderController::class, 'approveOrder'])->middleware(['auth:sanctum', 'checkAction:all,update-order']);


Route::controller(OrderController::class)->middleware('auth:sanctum')->prefix('client')->group(function (){
    Route::get('/getOrders', 'getAllOrderByUserID');
    Route::put('/cancelOrder', 'clientCancelOrder');
});
Route::post('/create-order', [OrderController::class, 'store']);
?>
