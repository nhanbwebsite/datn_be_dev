<?php

use App\Http\Controllers\RolePermissionController;

Route::controller(RolePermissionController::class)->middleware(['auth:sanctum', 'checkAction:all,view-role-permission,create-role-permission,update-role-permission,delete-role-permission'])->prefix('role-permissions')->group(function (){
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
?>
