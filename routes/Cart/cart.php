<?php

use App\Http\Controllers\CartController;

Route::controller(CartController::class)->middleware('auth:sanctum')->prefix('carts')->group(function(){
    Route::get('/view', 'show')->middleware('checkAction:all,view-cart');
    Route::post('/add', 'addToCart')->middleware('checkAction:all,view-cart');
    Route::put('/update', 'update')->middleware('checkAction:all,view-cart');
    Route::delete('/delete-detail/{product_id}/{variant_id}/{color_id}', 'deleteDetail')->middleware('checkAction:all,view-cart');
    Route::delete('/delete-cart', 'destroyCart')->middleware('checkAction:all,view-cart');
});

?>
