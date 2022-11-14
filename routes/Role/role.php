<?php

use App\Http\Controllers\RoleController;

Route::controller(RoleController::class)->middleware(['auth:sanctum'])->prefix('roles')->group(function(){
    Route::get('/', 'index')->middleware(['checkAction:all,view-role']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-role']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-role']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-role']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-role']);
});

?>
