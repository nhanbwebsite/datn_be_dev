<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\productAmountByWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
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
                 $data_total_revenue = DB::table('cart_details')
                ->join('carts','cart_details.cart_id','carts.id')
                ->select(DB::raw('SUM(cart_details.price) - SUM(carts.fee_ship) as total'))
                ->where('carts.deleted_at',null)
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
                return response()->json([
                    "data_total_product"              => $data_total_product,
                    'data_total_categories'           => $data_total_categories,
                    'data_total_user'                 => $data_total_user,
                    'data_total_admin'  => $data_total_admin,
                    'data_quantity_product_by_brands' => $data_quantity_product_by_brands,
                    'data_total_revenue'              => number_format($data_total_revenue->total) . ' VNĐ',
                    'data_product_by_subcate'         => $data_product_by_subcate
                ],200);
                // $data = DB::table('products')
                // ->select('sub_categories.sub_category_name',DB::raw('SUM(products.product_quantity) as total') )
                // ->join('sub_categories','products.id_sub_cate','sub_categories.id')
                // ->groupBy('sub_categories.sub_category_name')
                // ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
