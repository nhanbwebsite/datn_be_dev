<?php

use App\Http\Controllers\CityController;

Route::controller(CityController::class)->prefix('cities')->group(function (){
    Route::get('/', 'index');
    Route::get('/{province_id}', 'getDistrict');
    Route::get('/{province_id}/{district_id}', 'getWard');
});

?>
