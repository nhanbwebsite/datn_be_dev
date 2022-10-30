<?php
    use App\Http\Controllers\ProductImportSlipController;

    Route::controller(ProductImportSlipController::class)->middleware(['auth:sanctum'])->prefix('productImportSlip')->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
    ?>
