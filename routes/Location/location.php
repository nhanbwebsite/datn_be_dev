<?php
use App\Http\Controllers\locationController;

Route::prefix('location')->group(function (){
    Route::get('/getAllProvince',[locationController::class,'ProvinceAll']);
    Route::get('/getAllDistrict',[locationController::class,'DistrictAll']);
    Route::get('/getAllWard',[locationController::class,'WardAll']);

});
