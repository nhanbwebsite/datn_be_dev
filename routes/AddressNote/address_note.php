<?php

use App\Http\Controllers\AddressNoteController;

Route::controller(AddressNoteController::class)->middleware(['auth:sanctum'])->prefix('address-notes')->group(function(){
    Route::get('/', 'index')->middleware(['checkAction:all,view-address-note']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-address-note']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-address-note']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-address-note']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-address-note']);
});

Route::get('/client/get-address-notes', [AddressNoteController::class, 'getAddressNoteByCurrentUser'])->middleware(['auth:sanctum', 'checkAction:all,view-address-note']);

?>
