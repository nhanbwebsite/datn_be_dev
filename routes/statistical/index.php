<?php
use App\Http\Controllers\StatisticalController;



Route::controller(StatisticalController::class)->middleware('auth:sanctum')->prefix('admin_statis')->group(function (){
    Route::get('/statistical', 'index');
    // Route::get('/statisticalproductBySubcate','statisticalproductBySubcate');
    // Route::get('/statisticalTotalRevenue','statisticalTotalRevenue');
});
