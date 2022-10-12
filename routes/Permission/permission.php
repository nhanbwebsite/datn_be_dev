<?php

use App\Http\Controllers\PermissionController;

Route::controller(PermissionController::class)->middleware(['auth:sanctum', 'checkAction:all,view-permission,create-permission,update-permission,delete-permission'])->prefix('permissions')->group(function (){
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

?>
