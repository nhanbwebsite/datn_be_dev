<?php

use App\Http\Controllers\CartController;

Route::controller(CartController::class)->middleware('auth:sanctum')->prefix('carts')->group(function(){
    Route::get('/view', 'show');
    Route::post('/add', 'addToCart');
});

?>
