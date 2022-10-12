<?php

use App\Http\Controllers\RoleController;

Route::controller(RoleController::class)->middleware(['auth:sanctum', 'checkAction:all,view-role,create-role,update-role,delete-role'])->prefix('roles')->group(function(){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
