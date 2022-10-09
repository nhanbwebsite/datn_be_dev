<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubcategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/v1/categories',[CategoryController::class,'index']);

Route::post('/v1/categories',[CategoryController::class,'store']);

Route::get('/v1/categories/{id}',[CategoryController::class,'show']);

Route::patch('/v1/categories/{id}',[CategoryController::class,'update']);

Route::delete('/v1/categories/{id}',[CategoryController::class,'destroy']);




// subcategories routes

Route::get('/v1/subcategories',[SubcategoryController::class,'index']);

Route::post('/v1/subcategories',[SubcategoryController::class,'store']);

Route::get('/v1/subcategories/{id}',[SubcategoryController::class,'show']);

Route::patch('/v1/subcategories/{id}',[SubcategoryController::class,'update']);

Route::delete('/v1/subcategories/{id}',[SubcategoryController::class,'destroy']);

// post category routes
