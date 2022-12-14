<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientOrderStatusCollection;
use App\Http\Resources\OrderStatusCollection;
use App\Http\Resources\OrderStatusResource;
use App\Http\Validators\OrderStatus\OrderStatusUpsertValidator;
use App\Models\Order;
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
            DB::beginTransaction();
            $orderStatus = OrderStatus::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
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
            'message' => 'Tr???ng th??i ????n h??ng ['.$orderStatus->name.'] ???? ???????c t???o th??nh c??ng !',
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
                    'message' => 'Kh??ng t??m th???y tr???ng th??i ????n h??ng !',
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
                    'message' => 'Tr???ng th??i ???? t???n t???i !',
                ], 400);
            }
            DB::beginTransaction();
            $orderStatus = OrderStatus::find($id);
            if(empty($orderStatus)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tr???ng th??i ????n h??ng kh??ng t???n t???i !',
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
            'message' => 'Tr???ng th??i ????n h??ng ['.$orderStatus->name.'] ???? ???????c c???p nh???t !',
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
                    'message' => 'Tr???ng th??i ????n h??ng kh??ng t???n t???i !',
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
            'message' => '???? x??a ['.$data->name.']',
        ]);
    }

    public function clientGetOrderStatus(){
        try{
            $data = OrderStatus::where('is_active', 1)->get();
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
        return response()->json(new ClientOrderStatusCollection($data));
    }

    public function getStatusProcessByOrder($code){
        try{
            $order = Order::where('code', $code)->first();
            $process = ORDER_STATUS_PROCESS;
            foreach(ORDER_STATUS_PROCESS as $k => $status){
                if($status == $order->status){
                    for($i = 0; $i < $k; $i++){
                        unset($process[$i]);
                    }
                }
            }
            $result = [];
            foreach($process as $item){
                $stt = OrderStatus::where('id', $item)->where('is_active', 1)->first();
                $result[] = $stt;
            }

            $result[] = OrderStatus::where('id', ORDER_STATUS_COMPLETED)->where('is_active', 1)->first();
            $result[] = OrderStatus::where('id', ORDER_STATUS_CANCELED)->where('is_active', 1)->first();
            $result[] = OrderStatus::where('id', ORDER_STATUS_RETURNED)->where('is_active', 1)->first();
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
        return response()->json(new ClientOrderStatusCollection($result));
    }
}
