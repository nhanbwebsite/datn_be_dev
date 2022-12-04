<?php
use App\Http\Controllers\PostCategoryController;

    Route::prefix('post-categories')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[PostCategoryController::class,'index']);
        Route::post('/',[PostCategoryController::class,'store'])->middleware(['checkAction:all,create-post']);
        Route::get('/{id}',[PostCategoryController::class,'show']);
        Route::put('/{id}',[PostCategoryController::class,'update'])->middleware(['checkAction:all,update-post']);
        Route::delete('/{id}',[PostCategoryController::class,'destroy'])->middleware(['checkAction:all,delete-post']);
        Route::get('/{id}/get-posts',[PostCategoryController::class,'loadPostByCate']);
    });
