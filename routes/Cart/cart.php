<?php

use App\Http\Controllers\CartController;

Route::controller(CartController::class)->middleware('auth:sanctum')->prefix('carts')->group(function(){
    Route::get('/view', 'show');
    Route::post('/add', 'addToCart');
    Route::put('/update', 'update');
    Route::delete('/delete-detail/{product_id}', 'deleteDetail');
    Route::delete('/delete-cart', 'destroyCart');
});

?>
