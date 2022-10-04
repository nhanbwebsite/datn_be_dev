<?php

use App\Http\Controllers\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

require __DIR__.'/Auth/auth.php';

require __DIR__.'/User/user.php';


Route::get('/v1/categories',[Categories::class,'index']);

Route::get('/v1/categories/{category}',[Categories::class,'show']);

