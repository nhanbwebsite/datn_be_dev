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
        $dataReturn = '';
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
                if(auth('sanctum')->user()){
                    if(auth('sanctum')->user()->store_id){
                        $query->where('store_id', auth('sanctum')->user()->store_id);
                    }
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

        // $validator->validate($input);
        $details = $input['details'];
        foreach($input['details'] as $key => $value){
            $validatorDetail->validate($value);
        }
        try {
            DB::beginTransaction();
            // Phi???u nh???p
            if(isset($request->name) && isset($request->warehouse_id)){
                foreach($request->name as $key => $value){
                    $ProductImportSlip = ProductImportSlipModel::create([
                        'name' => $value, // t??n phi???u nh???p
                        'code' => strtoupper('PN'.date('YmdHis', time())),
                        'warehouse_id' => $request->warehouse_id[$key],
                        'note' => $request->note[$key],
                        'created_by'=> auth('sanctum')->user()->id,
                        'updated_by' => auth('sanctum')->user()->id,
                    ]);

                    //  chi ti???t phi???u nh???p
                    // dd($details);
                  $dataProvariantDetails =  ProductImportSlipDetail::create([
                        'product_import_slip_id' => $ProductImportSlip->id,
                        'product_id' => $details[$key]['product_id'],
                        'pro_variant_id' => $details[$key]['pro_variant_id'],
                        'color_id' => $details[$key]['color_id'],
                        'quantity_import' => $details[$key]['quantity_import'],
                        'price_import' => $details[$key]['price_import'],
                        'created_by' => auth('sanctum')->user()->id,
                        'updated_by' => auth('sanctum')->user()->id,
                    ]);
                    // dd($dataProvariantDetails);
                    //  Th??m v??o s??? l?????ng s???n ph???m ??? kho t???ng
                    $check = productAmountByWarehouse::where('pro_variant_id', $dataProvariantDetails['pro_variant_id'])
                                                      ->where('product_id', $request->product_id)
                                                     ->where('warehouse_id', $request->warehouse_id)->first();

    //  ki???m tra ??i???u ki???n ????? c???ng th??m s??? l?????ng ho???c t???o m???i n???u s???n ph???m ???? ch??a t???ng th??m th?? s??? t???o m???i
                    if(!empty($check)){
                        $check->product_amount += $details[$key]['quantity_import'];
                        $check->updated_by = auth('sanctum')->user()->id;
                        $check->save();
                    }else{
                        // dd( $details[$key]);
                        productAmountByWarehouse::create([
                            'product_id' => $dataProvariantDetails['product_id'],
                            'pro_variant_id' => $dataProvariantDetails['pro_variant_id'],
                            'color_id' => $details[$key]['color_id'],
                            'product_amount' => $details[$key]['quantity_import'],
                            'warehouse_id' => $request->warehouse_id[$key],
                            'created_by' => auth('sanctum')->user()->id,
                            'updated_by' => auth('sanctum')->user()->id,
                        ]);
                    }
    //k???t th??c ki???m tra
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
            'message' => '['.$ProductImportSlip->name.'] ???? ???????c t???o th??nh c??ng !',
            'data' => $ProductImportSlip
        ]);
    }    /**
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
                    'message' => 'Kh??ng t??m th???y th??ng tin thi????u ph????n san ph????m !',
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
            'warehouse_id' => 'required',
            'product_amount' => 'required'
        ];
        $messages = [
            'name.required' => ':attribute kh??ng ???????c ????? tr???ng !',
            'warehouse_id.required' => ':attribute kh??ng ????????c ?????? tr????ng !',
            'product_id.required' => ':attribute kh??ng ????????c ?????? tr????ng !',
            'product_amount.required' => ':attribute kh??ng ????????c ?????? tr????ng'
        ];
        $attributes = [
            'name' => 'T??n s???n ph???m',
            'warehouse_id' => 'Kho sa??n ph????m',
            'product_id' => 'T??n Sa??n ph????m',
            'product_amount' => 'S???? l??????ng sa??n ph????m'
        ];

        try {
            DB::beginTransaction();

            $validator = Validator::make($request->only(['name','warehouse_id','product_id','product_amount']), $rules, $messages, $attributes);
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
                'warehouse_id'=> $request->warehouse_id,
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
            'message' => '???? ???????c c????p nh????t th??nh c??ng !',
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
                   'message' => 'Sa??n ph????m kh??ng t????n ta??i !'
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
            'message' => '???? x??a phi????u nh????p ['.$data->name.']',
        ]);

    }

    public function getproductImportSlipDetails(){
        // kh??ng ph??n trang
        $data = ProductImportSlipDetail::all();
        return response()->json([
            'status'=> 'success',
            'data' => $data
        ],200);
    }

    public function getproductImportSlipDetailsByID($id){
        // kh??ng ph??n trang
        $data = ProductImportSlipDetail::find($id);
        return response()->json([
            'status'=> 'success',
            'data' => $data
        ],200);
    }

}
