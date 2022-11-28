<?php
use App\Http\Controllers\PostCategoryController;

    Route::prefix('postCate')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[PostCategoryController::class,'index'])->middleware(['checkAction:all,view-postCate']);
        Route::post('/',[PostCategoryController::class,'store'])->middleware(['checkAction:all,create-postCate']);
        Route::get('/{id}',[PostCategoryController::class,'show'])->middleware(['checkAction:all,view-postCate']);
        Route::put('/{id}',[PostCategoryController::class,'update'])->middleware(['checkAction:all,update-postCate']);
        Route::delete('/{id}',[PostCategoryController::class,'destroy'])->middleware(['checkAction:all,delete-postCate']);
        Route::post('/{id}',[PostCategoryController::class,'loadPostByCate'])->middleware(['checkAction:all,view-postCate']);

    });
