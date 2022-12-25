<?php
use App\Http\Controllers\StatisticalController;



Route::controller(StatisticalController::class)->middleware('auth:sanctum')->prefix('admin_statis')->group(function (){
    Route::get('/statistical', 'index');

    // Chart
    Route::get('/revenue', 'revenueStatistics'); // Doanh thu trong ngày/ tháng/ năm
    Route::get('/order', 'orderStatistics'); // Đơn hàng trong ngày/ tháng/ năm

    // Card
    Route::get('/popular-product', 'top5PopularProduct'); // Top 5 Sản phẩm phổ biến (được mua nhiều)
    Route::get('/total-order', 'totalOrder'); // Tổng số đơn hàng (có chia trạng thái)
    Route::get('/revenue-day', 'revenueStatisticsToDay'); // Doanh thu trong ngày (Có tổng doanh thu và doanh thu hôm qua)

    // List
    Route::get('/new-customer', 'newCustomer'); // Khách hàng (Đăng ký) mới
    Route::get('/new-order', 'newOrder'); // Đơn hàng mới

    // Route::get('/statisticalproductBySubcate','statisticalproductBySubcate');
    // Route::get('/statisticalTotalRevenue','statisticalTotalRevenue');
});
