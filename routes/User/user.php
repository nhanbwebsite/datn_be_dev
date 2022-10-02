<?php

use App\Http\Controllers\UserController;

Route::controller(UserController::class)->middleware(['auth:sanctum', 'checkAction:all,view-user,create-user,update-user,delete-user'])->prefix('users')->group(function (){
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::delete('/{id}', 'destroy');
});
?>
