<?php

namespace App\Http\Controllers;

use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
use App\Http\Validators\Coupon\CouponCreateValidator;
use App\Http\Validators\Coupon\CouponUpdateValidator;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = isset($request->limit) && $request->limit > 0 ? $request->limit : 10;
        try{
            $data = Coupon::where('is_active', $input['is_active'] ?? 1)->where(function ($query) use ($input){
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['type'])){
                    $query->where('type', $input['type']);
                }
                if(!empty($input['status'])){
                    $query->where('status', $input['status']);
                }
            })->orderBy('created_at', 'desc')->paginate($input['limit']);
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
        return response()->json(new CouponCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CouponCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();

            $data = Coupon::create([
                'code' => strtoupper($input['code']),
                'type' => strtoupper($input['type']),
                'discount_value' => $input['discount_value'],
                'max_use' => $input['max_use'],
                'status' => $input['status'],
                'promotion_id' => $input['promotion_id'],
                'is_active' => $input['is_active'] ?? 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            DB::commit();
        }
        catch(HttpException $e){
            DB::rollback();
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
            'message' => 'Đã tạo mã giảm giá ['.$data->code.'] thành công !'
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
           $data = Coupon::find($id);
           if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy mã giảm giá !',
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
            'data' => new CouponResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, CouponUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();

            $data = Coupon::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mã giảm giá không tồn tại !',
                ], 404);
            }

            $data->type = $input['type'] ?? $data->type;
            $data->discount_value = $input['discount_value'] ?? $data->discount_value;
            $data->max_use = $input['max_use'] ?? $data->max_use;
            $data->status = $input['status'] ?? $data->status;
            $data->promotion_id = $input['promotion_id'] ?? $data->promotion_id;
            $data->is_active = $input['is_active'] ?? $data->is_active;
            $data->updated_by = $user->id;
            $data->save();

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
            'message' => 'Đã cập nhật mã giảm giá ['.$data->code.'] !',
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
        $user = $request->user();
        try{
            DB::beginTransaction();

            $data = Coupon::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mã giảm giá không tồn tại !',
                ], 404);
            }
            $data->deleted_by = $user->id;
            $data->save();
            $data->delete();

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
            'message' => 'Đã xóa mã giảm giá ['.$data->code.'] !',
        ]);
    }
}
