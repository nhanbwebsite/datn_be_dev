<?php

use App\Http\Controllers\UserController;

Route::controller(UserController::class)->middleware(['auth:sanctum'])->prefix('users')->group(function (){
    Route::get('/', 'index')->middleware(['checkAction:all,view-user']);
    Route::get('/{id}', 'show')->middleware(['checkAction:all,view-user']);
    Route::post('/', 'store')->middleware(['checkAction:all,create-user']);
    Route::put('/{id}', 'update')->middleware(['checkAction:all,update-user']);
    Route::delete('/{id}', 'destroy')->middleware(['checkAction:all,delete-user']);
});
?>
