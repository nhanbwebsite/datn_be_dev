<?php

use App\Http\Controllers\RolePermissionController;

Route::controller(RolePermissionController::class)->prefix('role-permissions')->group(function (){
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
?>
