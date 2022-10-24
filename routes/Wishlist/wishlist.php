<?php

use App\Http\Controllers\WishlistController;

Route::controller(WishlistController::class)->prefix('wishlists')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::delete('/{id}', 'destroy');
});

?>
