<?php
    use App\Http\Controllers\ProductImportSlipController;
    use App\Http\Controllers\ProductController;

    Route::controller(ProductImportSlipController::class)->middleware(['auth:sanctum'])->prefix('productImportSlip')->group(function (){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');

    });

    Route::prefix('product_import_slip_details')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/', [ProductImportSlipController::class,'getproductImportSlipDetails']);
        Route::get('/{id}', [ProductImportSlipController::class,'getproductImportSlipDetailsByID']);
        // Route::post('/', 'store');
        // Route::patch('/{id}', 'update');
        // Route::delete('/{id}', 'destroy');
    });
    Route::get('/productsinfoimport', [ProductController::class,'getproductsImportSlip']);
?>
