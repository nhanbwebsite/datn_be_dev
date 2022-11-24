<?php
use App\Http\Controllers\ColorsController;
Route::prefix('colors')->group(function(){
    Route::get('/all',[ColorsController::class,'index']);

    Route::post('/create',[ColorsController::class,'store']);

    Route::get('/{id}',[ColorsController::class,'show']);

    Route::patch('/{id}',[ColorsController::class,'update']);

    Route::delete('/{id}',[ColorsController::class,'destroy']);
});
