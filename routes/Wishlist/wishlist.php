<?php

use App\Http\Controllers\WishlistController;

Route::controller(WishlistController::class)->middleware(['auth:sanctum', 'checkAction:all,view-wishlist,create-wishlist,update-wishlist,delete-wishlist'])->prefix('wishlists')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::delete('/{id}', 'destroy');
});

?>
