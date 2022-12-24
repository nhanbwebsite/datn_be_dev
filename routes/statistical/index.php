<?php
use App\Http\Controllers\StatisticalController;



Route::controller(StatisticalController::class)->middleware('auth:sanctum')->prefix('admin_statis')->group(function (){
    Route::get('/statistical', 'index');
    Route::get('/revenue-day', 'revenueStatisticsToDay'); // Doanh thu trong ngày
    Route::get('/popular-product', 'top10PopularProduct'); // Top 10 Sản phẩm phổ biến (được mua nhiều)
    Route::get('/new-customer', 'newCustomer'); // Khách hàng (Đăng ký) mới trong ngày
    Route::get('/new-order', 'newOrder'); // Đơn hàng mới trong ngày
    Route::get('/total-order', 'totalOrder'); // Tổng số đơn hàng (có chia trạng thái);
    // Route::get('/statisticalproductBySubcate','statisticalproductBySubcate');
    // Route::get('/statisticalTotalRevenue','statisticalTotalRevenue');
});
