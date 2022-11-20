<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentMethodClientCollection;
use App\Http\Resources\PaymentMethodCollection;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Validators\PaymentMethod\PaymentMethodUpsertValidator;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit;
        try{
            $data = PaymentMethod::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input) {
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['is_active'])){
                    $query->where('is_active', $input['is_active']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new PaymentMethodCollection($data);
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
        return response()->json($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PaymentMethodUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $paymentMethod = PaymentMethod::create([
                'name' => $input['name'],
                'code' => strtoupper($input['code']),
                'is_active' => $input['is_active'] ?? 1,
                'created_by' => auth('sanctum')->user()->id,
                'updated_by' => auth('sanctum')->user()->id,
            ]);
            DB::commit();
        }
        catch(HttpException $e){
            DB::rollBack();
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
            'status' => 'success',
            'message' => 'Trạng thái đơn hàng ['.$paymentMethod->name.'] đã được tạo thành công !',
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
        try{
            $data = PaymentMethod::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy phương thức thanh toán !',
                ], 404);
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
            'status' => 'success',
            'data' => new PaymentMethodResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PaymentMethodUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $user = $request->user();
            $paymentMethod = PaymentMethod::find($id);
            if(empty($paymentMethod)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phương thức thanh toán không tồn tại !',
                ], 404);
            }
            $paymentMethod->name = $request->name ?? $paymentMethod->name;
            $paymentMethod->code = strtoupper($request->code) ?? $paymentMethod->code;
            $paymentMethod->is_active = $request->is_active ?? $paymentMethod->is_active;
            $paymentMethod->updated_by = $user->id;
            $paymentMethod->save();

            DB::commit();
        }
        catch(HttpException $e){
            DB::rollBack();
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
            'status' => 'success',
            'message' => 'Phương thức thanh toán ['.$paymentMethod->name.'] đã được cập nhật !',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try{
            DB::beginTransaction();
            $user = $request->user();
            if(!is_array($id)){
                $data = PaymentMethod::find($id);
                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy người dùng !',
                    ], 404);
                }
                $data->deleted_by = $user->id;
                $data->save();

                $data->delete();
            }

            DB::commit();
        }
        catch(HttpException $e){
            DB::rollBack();
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
            'status' => 'success',
            'message' => 'Đã xóa ['.$data->name.']',
        ]);
    }

    public function getClientPaymentMethods(){
        try{
            $data = PaymentMethod::where('is_active', 1)->orderBy('created_at', 'desc')->get();
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
        return response()->json(new PaymentMethodClientCollection($data));
    }
}
