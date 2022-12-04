<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Validators\Order\OrderCreateValidator;
use App\Http\Validators\Order\OrderDetailCreateValidator;
use App\Http\Validators\Order\OrderUpdateValidator;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\VNPayOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit ?? 10;
        try{
            $data = Order::with(['details', 'createdBy'])
            ->where(function ($query) use ($input) {
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['status'])){
                    $query->where('status', $input['status']);
                }
                if(!empty($input['product_id'])){
                    $query->whereHas('details', function($q) use($input) {
                        $q->where('product_id', $input['product_id']);
                    });
                }
                if(!empty($input['user_id'])){
                    $query->whereHas('createdBy', function($q) use($input) {
                        $q->where('user_id', $input['user_id']);
                    });
                }
                if(!empty($input['from'])){
                    $query->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime($input['from'])));
                    if(!empty($input['to'])){
                        $query->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime($input['to'])));
                    }
                }
            })->orderBy('orders.created_at', 'desc')->paginate($input['limit']);
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
        return response()->json(new OrderCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OrderCreateValidator $orderCreateValidator, OrderDetailCreateValidator $orderDetailCreateValidator)
    {
        $input = $request->all();
        $input['total'] = $input['fee_ship'];
        $orderCreateValidator->validate($input);
        foreach($input['details'] as $key => $value){
            $orderDetailCreateValidator->validate($value);
            $input['total'] += $value['price'];
        }
        $user = $request->user();
        try{
            DB::beginTransaction();

            $create = Order::create([
                'code' => $input['code'],
                'user_id' => $user->id,
                'address' => $input['address'],
                'ward_id' => $input['ward_id'],
                'district_id' => $input['district_id'],
                'province_id' => $input['province_id'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'total' => $input['total'],
                'discount' => $input['discount'] ?? 0,
                'coupon_id' => $input['coupon_id'] ?? null,
                'fee_ship' => $input['fee_ship'] ?? 0,
                'payment_method_id' => $input['payment_method_id'],
                'shipping_method_id' => $input['shipping_method_id'],
                'status' => ORDER_STATUS_NEW,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            foreach($input['details'] as $value){
                OrderDetail::create([
                    'order_id' => $create->id,
                    'product_id' => $value['product_id'],
                    'variant_id' => $value['variant_id'],
                    'quantity' => $value['quantity'],
                    'price' => $value['price'],
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }

            if(!empty($input['coupon_id'])){
                CouponOrder::create([
                    'coupon_id' => $input['coupon_id'],
                    'order_id' => $create->id,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }

            if($input['payment_method_id'] == PAYMENT_METHOD_VNPAY){
                $checkVNPay = VNPayOrder::where('vnp_TxnRef', $create->code)->first();
                if(!empty($checkVNPay) && ($checkVNPay->vnp_ResponseCode == '00' && $checkVNPay->vnp_TransactionStatus == '00')){
                    $create->status = ORDER_STATUS_SHIPPING;
                    $create->save();
                }
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
            'message' => 'Đặt hàng thành công ! Mã đơn hàng của bạn là: '.$create->code
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
            $data = Order::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại !',
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
            'data' => new OrderResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, OrderUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();

            $update = Order::find($id);
            if(empty($update)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đơn hàng không tồn tại !',
                ], 404);
            }

            if(!empty($input['coupon_id'])){
                $orderUser = Order::where('user_id', $user->id)->get()->toArray();
            }

            $update->status = $input['status'] ?? $update->status;
            $update->address = $input['address'] ?? $update->address;
            $update->ward_id =  $input['ward_id'] ?? $update->ward_id;
            $update->district_id=  $input['district_id'] ?? $update->ward_id;
            $update->province_id=  $input['province_id'] ?? $update->ward_id;
            $update->phone=  $input['phone'] ?? $update->phone;
            $update->email=  $input['email'] ?? $update->email;
            $update->shipping_method_id = $input['shipping_method_id'] ?? $update->shipping_method_id;
            $update->payment_method_id = $input['payment_method_id'] ?? $update->payment_method_id;
            $update->updated_by = $user->id;
            $update->save();

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
            'message' => 'Đã cập nhật đơn hàng ['.$update->code.'] !',
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

            $data = Order::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại !'
                ], 404);
            }

            if(!empty($data->details)){
                foreach($data->details as $detail){
                    $detailDel = OrderDetail::find($detail->id);
                    $detailDel->deleted_by = $user->id;
                    $detailDel->save();
                    $detailDel->delete();
                }
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
            'message' => 'Đã xóa đơn hàng ['.$data->code.'] !'
        ]);
    }

    public function getAllOrderByUserID(Request $request){
        $input = $request->all();
        $input['limit'] = $request->limit ?? 10;
        $user = $request->user();
        try{
            $data = Order::where(function ($query) use ($input) {
                if(!empty($input['status'])){
                    $query->where('status', $input['status']);
                }
                if(!empty($input['from'])){
                    $query->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime($input['from'])));
                    if(!empty($input['to'])){
                        $query->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime($input['to'])));
                    }
                }
            })->where('user_id', $user->id)->orderBy('orders.created_at', 'desc')->paginate($input['limit']);
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
        return response()->json(new OrderCollection($data));
    }
}
