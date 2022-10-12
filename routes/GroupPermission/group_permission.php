<?php

use App\Http\Controllers\GroupPermissionController;

Route::controller(GroupPermissionController::class)->middleware(['auth:sanctum', 'checkAction:all,view-group-permission,create-group-permission,update-group-permission,delete-group-permission'])->prefix('group-permissions')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
