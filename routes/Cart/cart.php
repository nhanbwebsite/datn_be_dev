<?php

use App\Http\Controllers\CartController;

Route::controller(CartController::class)->middleware('auth:sanctum')->prefix('carts')->group(function(){
    Route::get('/{id}', 'show');
    Route::post('/{product_id}', 'addToCart');
});

?>
