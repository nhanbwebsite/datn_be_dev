<?php

use App\Http\Controllers\GroupPermissionController;

Route::controller(GroupPermissionController::class)->middleware(['auth:sanctum'])->prefix('group-permissions')->group(function () {
    Route::get('/', 'index')->middleware(['checkAction:all,view-group-permission']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-group-permission']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-group-permission']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-group-permission']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-group-permission']);
});

?>
