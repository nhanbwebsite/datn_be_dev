<?php

use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->prefix('auth')->group(function (){
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});

Route::controller(AuthController::class)->middleware(['auth:sanctum'])->group(function (){
    Route::post('/me', 'me');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh');
});

// Route::controller(AuthController::class)->group(function () {
//     Route::get('/sms', 'sendSMS');
// });
?>
