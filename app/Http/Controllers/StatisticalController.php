<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewCustomerStatistic;
use App\Http\Resources\NewOrderStatistic;
use Illuminate\Http\Request;
use App\Models\productAmountByWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Post;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StatisticalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                // thống kê tổng số lượng sản phẩm của thương hiệu
                $data_quantity_product_by_brands = DB::table('products')
                ->select('brands.brand_name',DB::raw('count(*) as total'))
                ->join('sub_categories','products.subcategory_id','sub_categories.id')
                ->join('brands','sub_categories.brand_id','brands.id')
                ->where('products.is_active',1)
                ->where('products.deleted_at',null)
                ->where('brands.is_active',1)
                ->groupBy('brands.brand_name')
                ->get();
                // Tổng doanh thu
                 $data_total_revenue = DB::table('order_details')
                ->join('orders','order_details.order_id','orders.id')
                ->select(DB::raw('SUM(order_details.price) - SUM(orders.fee_ship) as total'))
                ->where('orders.deleted_at',null)
                ->first();
                // products_by_subcategories
                $data_product_by_subcate = DB::table('products')
                ->select('sub_categories.name',DB::raw('count(*) as total'))
                ->join('sub_categories','products.subcategory_id','sub_categories.id')
                ->where('products.deleted_at',null)
                ->groupBy('sub_categories.name')
                ->get();
                //  thống kê sản phẩm
                $data_total_product = Product::count();
                //  thống kê danh mục
                $data_total_categories = Category::count();
                // thống kê User
                $data_total_user = User::where('role_id',4)->count();
                // admin
                $data_total_admin = User::where('role_id',1)->orWhere('role_id',4)->count();
                // post
                $data_total_posts = Post::count();

                // total revenue a day


                function getLastWeekDates()
                {
                    $lastWeek = array();

                    $prevMon = abs(strtotime("previous monday"));
                    $currentDate = abs(strtotime("today"));
                    $seconds = 86400; //86400 seconds in a day

                    $dayDiff = ceil( ($currentDate-$prevMon)/$seconds );

                    if( $dayDiff < 7 )
                    {
                        $dayDiff += 1; //if it's monday the difference will be 0, thus add 1 to it
                        $prevMon = strtotime( "previous monday", strtotime("-$dayDiff day") );
                    }

                    $prevMon = date("Y-m-d",$prevMon);

                    // create the dates from Monday to Sunday
                    for($i=0; $i<7; $i++)
                    {
                        $d = date("Y-m-d", strtotime( $prevMon." + $i day") );
                        // dd($d);
                        $lastWeek[]=$d;
                    }




                    return $lastWeek;
                }

                // dd(getLastWeekDates());


                return response()->json([
                    "data_total_product"              => $data_total_product,
                    'data_total_categories'           => $data_total_categories,
                    'data_total_user'                 => $data_total_user,
                    'data_total_admin'  => $data_total_admin,
                    'data_quantity_product_by_brands' => $data_quantity_product_by_brands,
                    'data_total_revenue'              => number_format($data_total_revenue->total) . ' VNĐ',
                    'data_product_by_subcate'         => $data_product_by_subcate,
                    'data_total_posts'                 => $data_total_posts
                ],200);



                // $data = DB::table('products')
                // ->select('sub_categories.sub_category_name',DB::raw('SUM(products.product_quantity) as total') )
                // ->join('sub_categories','products.id_sub_cate','sub_categories.id')
                // ->groupBy('sub_categories.sub_category_name')
                // ->get();
    }

    public function revenueStatistics(){
        $result = [];
        try{
            // daily
            for($i = 0; $i < 7; $i++){
                $day = date('d-m-Y', time()-$i*24*60*60);
                $dataDay = Order::where('status', ORDER_STATUS_COMPLETED)->whereDay('created_at', date('d', time()-$i*24*60*60))->get();
                if(!empty($dataDay)){
                    $total_revenue_day = 0;
                    foreach($dataDay as $key => $value){
                        if(!empty($value)){
                            $total_revenue_day += $value->total;
                        }
                    }
                }
                $result['daily'][$day] = $total_revenue_day;
            }

            //monthly
            for($j = 0; $j < 6; $j++){
                $month = date('m-Y', time()-$j*30*24*60*60);
                $dataMonth = Order::where('status', ORDER_STATUS_COMPLETED)->whereMonth('created_at', date('m', time()-$j*30*24*60*60))->get();
                if(!empty($dataMonth)){
                    $total_revenue_month = 0;
                    foreach($dataMonth as $key => $value){
                        if(!empty($value)){
                            $total_revenue_month += $value->total;
                        }
                    }
                }
                $result['monthly'][$month] = $total_revenue_month;
            }

            //yearly
            for($k = 0; $k <= 5; $k++){
                $year = date('Y', time()-$k*12*30*24*60*60);
                $dataYear = Order::where('status', ORDER_STATUS_COMPLETED)->whereYear('created_at', date('Y', time()-$k*12*30*24*60*60))->get();
                if(!empty($dataYear)){
                    $total_revenue_year = 0;
                    foreach($dataYear as $key => $value){
                        if(!empty($value)){
                            $total_revenue_year += $value->total;
                        }
                    }
                }
                $result['yearly'][$year] = $total_revenue_year;
            }
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'data' => $result ?? null,
        ]);
    }

    public function top5PopularProduct(){
        try{
            $result = [];
            $allProduct = Product::select('id', 'name', 'code')->where('is_active', 1)->get();
            foreach($allProduct as $key => $product){
                $count = OrderDetail::where('product_id', $product->id)->count();
                $result[$product->code] = [
                    'name' => $product->name,
                    'count' => $count,
                ];
            }
            $data = collect($result)->sortBy('count')->reverse()->take(5)->toArray();
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'data' => $data ?? null,
        ]);
    }

    public function newCustomer(){
        try{
            $data = User::orderBy('created_at', 'desc')->take(10)->get();
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json(new NewCustomerStatistic($data));
    }

    public function newOrder(){
        try{
            $data = Order::orderBy('created_at', 'desc')->take(10)->get();
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json(new NewOrderStatistic($data));
    }

    public function totalOrder(){
        try{
            $totalOrder = Order::select()->count();
            $statusNew = Order::where('status', ORDER_STATUS_NEW)->count();
            $statusApproved = Order::where('status', ORDER_STATUS_APPROVED)->count();
            $statusShipping = Order::where('status', ORDER_STATUS_SHIPPING)->count();
            $statusShipped = Order::where('status', ORDER_STATUS_SHIPPED)->count();
            $statusCompleted = Order::where('status', ORDER_STATUS_COMPLETED)->count();
            $statusCanceled = Order::where('status', ORDER_STATUS_CANCELED)->count();
            $statusReturned = Order::where('status', ORDER_STATUS_RETURNED)->count();
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'data' => [
                'total_order' => $totalOrder,
                'status_new' => [
                    'id' => ORDER_STATUS_NEW,
                    'name' => OrderStatus::find(ORDER_STATUS_NEW)->name ?? null,
                    'value' => $statusNew,
                ],
                'status_approved' => [
                    'id' => ORDER_STATUS_APPROVED,
                    'name' => OrderStatus::find(ORDER_STATUS_APPROVED)->name ?? null,
                    'value' => $statusApproved,
                ],
                'status_shipping' => [
                    'id' => ORDER_STATUS_SHIPPING,
                    'name' => OrderStatus::find(ORDER_STATUS_SHIPPING)->name ?? null,
                    'value' => $statusShipping,
                ],
                'status_shipped' => [
                    'id' => ORDER_STATUS_SHIPPED,
                    'name' => OrderStatus::find(ORDER_STATUS_SHIPPED)->name ?? null,
                    'value' => $statusShipped,
                ],
                'status_completed' => [
                    'id' => ORDER_STATUS_COMPLETED,
                    'name' => OrderStatus::find(ORDER_STATUS_COMPLETED)->name ?? null,
                    'value' => $statusCompleted,
                ],
                'status_canceled' => [
                    'id' => ORDER_STATUS_CANCELED,
                    'name' => OrderStatus::find(ORDER_STATUS_CANCELED)->name ?? null,
                    'value' => $statusCanceled,
                ],
                'status_returned' => [
                    'id' => ORDER_STATUS_RETURNED,
                    'name' => OrderStatus::find(ORDER_STATUS_RETURNED)->name ?? null,
                    'value' => $statusReturned,
                ],
            ]
        ]);
    }

    public function orderStatistics(){
        $result = [];
        try{
            // daily
            for($i = 0; $i < 7; $i++){
                $day = date('d-m-Y', time()-$i*24*60*60);
                $dataDay = Order::where('status', ORDER_STATUS_COMPLETED)->whereDay('created_at', date('d', time()-$i*24*60*60))->count();
                $result['daily'][$day] = $dataDay;
            }

            //monthly
            for($j = 0; $j < 6; $j++){
                $month = date('m-Y', time()-$j*30*24*60*60);
                $dataMonth = Order::where('status', ORDER_STATUS_COMPLETED)->whereMonth('created_at', date('m', time()-$j*30*24*60*60))->count();
                $result['monthly'][$month] = $dataMonth;
            }

            //yearly
            for($k = 0; $k <= 5; $k++){
                $year = date('Y', time()-$k*12*30*24*60*60);
                $dataYear = Order::where('status', ORDER_STATUS_COMPLETED)->whereYear('created_at', date('Y', time()-$k*12*30*24*60*60))->count();
                $result['yearly'][$year] = $dataYear;
            }
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'data' => $result ?? null,
        ]);
    }

    public function revenueStatisticsToDay(){
        $result = [];
        try{
            $data = Order::where('status', ORDER_STATUS_COMPLETED)->whereDate('created_at', '=', time())->get()->toArray();
            $data = Order::where('status', ORDER_STATUS_COMPLETED)->whereDay('created_at', date('d', time()))->get();
            if(empty($data)){
                $result['total_revenue_today'] = 0;
                $result['total_revenue_today_formatted'] = number_format(0).'đ';
            }
            $total_revenue_today = 0;
            foreach($data as $key => $value){
                if(!empty($value)){
                    $total_revenue_today += $value->total;
                }
            }
            $result['today'] = date('d-m-Y', time());
            $result['total_revenue_today'] = $total_revenue_today;
            $result['total_revenue_today_formatted'] = number_format($total_revenue_today).'đ';

            $orderYesterday = Order::where('status', ORDER_STATUS_COMPLETED)->whereDay('created_at', date('d', time()-24*60*60))->get();
            if(empty($orderYesterday)){
                $result['total_revenue_yesterday'] = 0;
                $result['total_revenue_yesterday_formatted'] = number_format(0).'đ';
            }
            $total_revenue_yesterday = 0;
            foreach($orderYesterday as $key => $value){
                if(!empty($value)){
                    $total_revenue_yesterday += $value->total;
                }
            }
            $result['total_revenue_yesterday'] = $total_revenue_yesterday;
            $result['total_revenue_yesterday_formatted'] = number_format($total_revenue_yesterday).'đ';

            $allCompletedOrder = Order::where('status', ORDER_STATUS_COMPLETED)->get();
            if(empty($allCompletedOrder)){
                $result['total_revenue'] = 0;
                $result['total_revenue_formatted'] = number_format(0).'đ';
            }
            $total_revenue = 0;
            foreach($allCompletedOrder as $key => $value){
                if(!empty($value)){
                    $total_revenue += $value->total;
                }
            }
            $result['total_revenue'] = $total_revenue;
            $result['total_revenue_formatted'] = number_format($total_revenue).'đ';

        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'data' => $data ?? [],
            'data' => $result,
        ]);
    }
}
