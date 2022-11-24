<?php
use App\Http\Controllers\PostController;

Route::prefix('admin')->group(function (){

    Route::post('/post',[PostController::class,'store']);
    Route::patch('/post/{id}',[PostController::class,'update']);
    Route::delete('/post/{id}',[PostController::class,'destroy']);
    Route::get('/v1/postByViews/',[PostController::class,'loadByViews']);
    Route::get('/v1/postByCate/',[PostController::class,'loadByViews']);

});
Route::get('posts/{id}',[PostController::class,'show']);
Route::get('/posts',[PostController::class,'index']);
