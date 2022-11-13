<?php
use App\Http\Controllers\PostController;

Route::prefix('admin')->group(function (){
    Route::get('/v1/post',[PostController::class,'index']);
    Route::post('/v1/post',[PostController::class,'store']);
    Route::patch('/v1/post/{id}',[PostController::class,'update']);
    Route::delete('/v1/post/{id}',[PostController::class,'destroy']);
    Route::get('/v1/post/{id}',[PostController::class,'show']);
    Route::get('/v1/postByViews/',[PostController::class,'loadByViews']);

});
