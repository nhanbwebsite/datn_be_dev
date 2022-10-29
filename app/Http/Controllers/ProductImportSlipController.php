<?php

namespace App\Http\Controllers;

use App\Models\ProductImportSlipModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductImportSlipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ProductImportSlipModel::paginate(9);
        return response()->json([
            'message' => 'Danh sách phiếu nhập sản phẩm',
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:6|max:255',
            'user_id' => 'required',
            'product_id' => 'required',
            'warehouse_id' => 'required',

            // 'store_id' => 'required'
        ];
        $messages = [
            'name.required' => ':attribute không được để trống !',
            'warehouse_id.required' => ':attribute không được để trống !',
            'user_id.required' => ':attribute không được để trống !',
            'product_id.required' => ':attribute không được để trống !',


        ];
        $attributes = [
            'name' => 'Tên sản phẩm',
            'brand_id' => 'Tên thương hiệu',
            'meta_description' => 'meta_description',
            'store_id.required' => 'Cửa hàng'
        ];

        try {
            DB::beginTransaction();

            $validator = Validator::make($request->only(['name','brand_id','meta_description','store_id']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }


            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            return  response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ],400);
        };
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
