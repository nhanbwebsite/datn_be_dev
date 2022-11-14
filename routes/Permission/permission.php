<?php

use App\Http\Controllers\PermissionController;

Route::controller(PermissionController::class)->middleware(['auth:sanctum'])->prefix('permissions')->group(function (){
    Route::get('/', 'index')->middleware(['checkAction:all,view-permission']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-permission']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-permission']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-permission']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-permission']);
});

?>
