<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderStatusCollection;
use App\Http\Resources\OrderStatusResource;
use App\Http\Validators\OrderStatus\OrderStatusUpsertValidator;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderStatusController extends Controller
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
            $data = OrderStatus::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input) {
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['is_active'])){
                    $query->where('is_active', $input['is_active']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new OrderStatusCollection($data);
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
    public function store(Request $request, OrderStatusUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            $check = OrderStatus::where('code', $request->code)->whereNull('deleted_at')->first();
            if(!empty($check)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Trạng thái đã tồn tại !',
                ], 400);
            }
            DB::beginTransaction();
            $orderStatus = OrderStatus::create([
                'name' => $request->name,
                'code' => $request->code,
                'sort_level' => $request->sort_level ?? null,
                'is_active' => $request->is_active ?? 1,
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
            'message' => 'Trạng thái đơn hàng ['.$orderStatus->name.'] đã được tạo thành công !',
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
            $data = OrderStatus::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy trạng thái đơn hàng !',
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
            'data' => new OrderStatusResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, OrderStatusUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $user = $request->user();
            $check = OrderStatus::where('code', $request->code)->whereNull('deleted_at')->first();
            if(empty($check)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Trạng thái đã tồn tại !',
                ], 400);
            }
            DB::beginTransaction();
            $orderStatus = OrderStatus::find($id);
            if(empty($orderStatus)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Trạng thái đơn hàng không tồn tại !',
                ], 404);
            }
            $orderStatus->name = $request->name ?? $orderStatus->name;
            $orderStatus->code = $request->code ?? $orderStatus->code;
            $orderStatus->sort_level = $request->sort_level ?? $orderStatus->sort_level;
            $orderStatus->is_active = $request->is_active ?? $orderStatus->is_active;
            $orderStatus->updated_by = $user->id;
            $orderStatus->save();
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
            'message' => 'Trạng thái đơn hàng ['.$orderStatus->name.'] đã được cập nhật !',
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
            $data = OrderStatus::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Trạng thái đơn hàng không tồn tại !',
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
            'message' => 'Đã xóa ['.$data->name.']',
        ]);
    }
}
