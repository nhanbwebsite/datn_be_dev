<?php

use App\Http\Controllers\AddressNoteController;

Route::controller(AddressNoteController::class)->middleware(['auth:sanctum', 'checkAction:all,view-address-note,create-address-note,update-address-note,delete-address-note'])->prefix('address-notes')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');

    Route::get('/client/get-data', 'getAddressNoteByCurrentUser');
});

?>
