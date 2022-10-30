<?php

namespace App\Http\Controllers;

use App\Models\ProductImportSlipModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Validator;
use App\Models\productAmountByWarehouse;
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

        if(count($data) == 0){
            return response()->json([
                'message' => 'Danh sách phiếu nhập sản phẩm hiện đang trống !',
            ],200);
        }

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
            'product_id' => 'required',
            'store_id' => 'required',
            // 'store_id' => 'required'
        ];
        $messages = [
            'name.required' => ':attribute không được để trống !',
            'store_id.required' => ':attribute không được để trống !',
            'product_id.required' => ':attribute không được để trống !',
        ];
        $attributes = [
            'name' => 'Tên sản phẩm',
            'store_id' => 'Kho sản phẩm',
            'product_id' => 'Tên Sản phẩm'
        ];

        try {
            DB::beginTransaction();

            $validator = Validator::make($request->only(['name','store_id','product_id']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

            $ProductImportSlip = ProductImportSlipModel::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'product_id' => $request->product_id,
                'store_id' => $request->store_id,
                'product_amount'=> $request->product_amount,
                'import_price' => $request->import_price,
                'create_by'=> auth('sanctum')->user()->id
            ]);

            // tim san pham trong table productAmountByWarehouse

            $findProByIdEndStoreId = productAmountByWarehouse::where('product_id',$ProductImportSlip->product_id)
                                     ->where('store_id',$ProductImportSlip->store_id)->first();
            if(empty($findProByIdEndStoreId)){
                $insertAmountByWarehouse = productAmountByWarehouse::create([
                   'product_id' => $ProductImportSlip->product_id,
                   'product_amount' => $ProductImportSlip->product_amount,
                   'store_id' => $ProductImportSlip->store_id
                ]);
            } else{
                $findProByIdEndStoreId->product_amount += $ProductImportSlip->product_amount;
                $findProByIdEndStoreId->save();
            }




            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return  response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ],$e->getStatusCode());
        };

        return response()->json([
            'status' => 'success',
            'message' => '['.$ProductImportSlip->name.'] đã được tạo thành công !',
            'data' => $ProductImportSlip
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            DB::beginTransaction();
            $data = ProductImportSlipModel::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy thông tin thiếu phẩn san phẩm !',
                ], 404);
            }
            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
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
        $rules = [
            'name' => 'required|min:6|max:255',
            'product_id' => 'required',
            'store_id' => 'required',
            'product_amount' => 'required'
        ];
        $messages = [
            'name.required' => ':attribute không được để trống !',
            'store_id.required' => ':attribute không được để trống !',
            'product_id.required' => ':attribute không được để trống !',
            'product_amount.required' => ':attribute không được để trống'
        ];
        $attributes = [
            'name' => 'Tên sản phẩm',
            'store_id' => 'Kho sản phẩm',
            'product_id' => 'Tên Sản phẩm',
            'product_amount' => 'Số lượng sản phẩm'
        ];

        try {
            DB::beginTransaction();

            $validator = Validator::make($request->only(['name','store_id','product_id','product_amount']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

          $data =  ProductImportSlipModel::where('id', $id)
            ->update([
                'name' =>$request->name,
                'slug'=> Str::slug($request->name),
                'product_id' => $request->product_id,
                'store_id'=> $request->store_id,
                'product_amount'=> $request->product_amount,
                'import_price' => $request->import_price,
                'update_by' => auth('sanctum')->user()->id,
        ]);

            DB::commit();
        } catch(HttpException $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ],$e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'đã được cập nhật thành công !',
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $data = ProductImportSlipModel::find($id);
            if(empty($data)){
                return response()->json([
                   'status' => 'error',
                   'message' => 'Sản phẩm không tồn tại !'
                ],404);
            }

            $data->delete_by = auth('sanctum')->user()->id;
            $data->save();
            $data->delete();
            DB::commit();
        } catch(HttpException $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa phiếu nhập ['.$data->name.']',
        ]);

    }
}
