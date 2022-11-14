<?php

use App\Http\Controllers\RolePermissionController;

Route::controller(RolePermissionController::class)->middleware(['auth:sanctum'])->prefix('role-permissions')->group(function (){
    Route::get('/', 'index')->middleware(['checkAction:all,view-role-permission']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-role-permission']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-role-permission']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-role-permission']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-role-permission']);
});
?>
