<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\PostCategoryController;

use App\Http\Controllers\ProductController;

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

require __DIR__ . '/Auth/auth.php';
require __DIR__ . '/User/user.php';
require __DIR__ . '/Role/role.php';
require __DIR__ . '/GroupPermission/group_permission.php';
require __DIR__ . '/Permission/permission.php';

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


//  products routes

Route::get('/v1/products',[ProductController::class,'index']);
Route::get('/v1/product/{id}',[ProductController::class,'show']);
Route::post('/v1/products',[ProductController::class,'store']);
Route::patch('/v1/product/{id}',[ProductController::class,'update']);
Route::delete('/v1/product/{id}',[ProductController::class,'destroy']);

Route::get('/v1/postcategories',[PostCategoryController::class,'index']);
Route::post('/v1/postcategories',[PostCategoryController::class,'store']);
Route::patch('/v1/postcategories/{id}',[PostCategoryController::class,'update']);
Route::delete('/v1/postcategories/{id}',[PostCategoryController::class,'destroy']);
Route::get('/v1/postcategories/{id}',[PostCategoryController::class,'show']);

//  Brands routes


Route::get('/v1/brands/',[BrandController::class,'index']);
Route::get('/v1/brands/{id}',[BrandController::class,'show']);
Route::patch('/v1/brands/{id}',[BrandController::class,'show']);
Route::delete('/v1/brands/{id}',[BrandController::class,'destroy']);
