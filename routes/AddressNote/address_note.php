<?php

use App\Http\Controllers\AddressNoteController;

Route::controller(AddressNoteController::class)->prefix('address-notes')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
