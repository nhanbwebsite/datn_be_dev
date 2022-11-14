<?php

use App\Http\Controllers\WishlistController;

Route::controller(WishlistController::class)->middleware(['auth:sanctum'])->prefix('wishlists')->group(function(){
    Route::get('/', 'index')->middleware('checkAction:all,view-wishlist');
    Route::post('/', 'store')->middleware('checkAction:all,create-wishlist');
    Route::delete('/{id}', 'destroy')->middleware('checkAction:all,delete-wishlist');
});

?>
