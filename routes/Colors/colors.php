<?php
use App\Http\Controllers\ColorsController;
Route::prefix('colors')->group(function(){
    Route::get('/all',[ColorsController::class,'index']);

    Route::post('/create',[ColorsController::class,'store'])->middleware('auth:sanctum');

    Route::get('/{id}',[ColorsController::class,'show']);

    Route::patch('/{id}',[ColorsController::class,'update'])->middleware('auth:sanctum');

    Route::delete('/{id}',[ColorsController::class,'destroy'])->middleware('auth:sanctum');
});
Route::get('/allColor',[ColorsController::class,'index']);
