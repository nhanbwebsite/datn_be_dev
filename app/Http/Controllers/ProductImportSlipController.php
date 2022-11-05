<?php

namespace App\Http\Controllers;

use App\Http\Resources\productImportSlipCollection;
use App\Http\Validators\ProductImportSlip\ProductImportSlipCreateValidator;
use App\Http\Validators\ProductImportSlip\ProductImportSlipDetailCreateValidator;
use App\Models\ProductImportSlipModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Validator;
use App\Models\productAmountByWarehouse;
use App\Models\ProductImportSlipDetail;

class ProductImportSlipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        try{
            $data = ProductImportSlipModel::where(function ($query) use ($input){
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['warehouse_id'])){
                    $query->where('warehouse_id', $input['warehouse_id']);
                }
                if(!empty($input['status'])){
                    $query->where('status', $input['status']);
                }
            })->orderBy('created_at', 'desc')->paginate($input['limit'] ?? 10);
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
        return response()->json(new productImportSlipCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProductImportSlipCreateValidator $validator, ProductImportSlipDetailCreateValidator $validatorDetail)
    {
        $input = $request->all();
        $validator->validate($input);
        foreach($input['details'] as $key => $value){
            $validatorDetail->validate($value);
        }
        try {
            DB::beginTransaction();

            $ProductImportSlip = ProductImportSlipModel::create([
                'name' => $input['name'],
                'code' => strtoupper('PN'.date('YmdHis', time())),
                'warehouse_id' => $input['warehouse_id'],
                'status' => $input['status'],
                'created_by'=> $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);

            $details = $input['details'];
            foreach($details as $key => $detail) {
                ProductImportSlipDetail::create([
                    'product_import_slip_id' => $ProductImportSlip->id,
                    'product_id' => $detail['product_id'],
                    'quantity_import' => $detail['quantity_import'],
                    'price_import' => $detail['price_import'],
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);

                $check = productAmountByWarehouse::where('product_id', $detail['product_id'])->where('warehouse_id', $request->warehouse_id)->first();

                if(!empty($check)){

                    $check->product_amount += $detail['quantity_import'];
                    $check->updated_by = $request->user()->id;
                    $check->save();
                }
                else{
                    productAmountByWarehouse::create([
                        'product_id' => $detail['product_id'],
                        'product_amount' => $detail['quantity_import'],
                        'warehouse_id' => $request->warehouse_id,
                        'created_by' => $request->user()->id,
                        'updated_by' => $request->user()->id,
                    ]);
                }
            }

            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
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
