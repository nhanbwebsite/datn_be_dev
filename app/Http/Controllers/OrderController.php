<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Http\Validators\Order\ApproveOrderValidator;
use App\Http\Validators\Order\CancelOrderValidator;
use App\Http\Validators\Order\ClientCancelOrderValidator;
use App\Http\Validators\Order\OrderCreateValidator;
use App\Http\Validators\Order\OrderDetailCreateValidator;
use App\Http\Validators\Order\OrderUpdateValidator;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\productAmountByWarehouse;
use App\Models\ProductVariant;
use App\Models\ProductVariantDetail;
use App\Models\User;
use App\Models\VNPayOrder;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $data = Order::where(function ($query) use ($input) {
                if(!empty($input['code'])){
                    $query->where('code', 'like', '%'.$input['code'].'%');
                }
                if(!empty($input['user_name'])){
                    $query->where('user_name', 'like', '%'.$input['user_name'].'%');
                }
                if(!empty($input['phone'])){
                    $query->where('phone', 'like', '%'.$input['phone'].'%');
                }
                if(!empty($input['status'])){
                    $query->where('status', $input['status']);
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
        $orderCreateValidator->validate($input);
        $order_code_cod = 'DH'.date('YmdHis', time());
        if(!empty($input['payment_method_id'])){
            if(!in_array($input['payment_method_id'], PAYMENT_METHOD_ID_ONLINE)){
                $input['code'] = $order_code_cod;
            }
        }
        foreach($input['details'] as $key => $value){
            $orderDetailCreateValidator->validate($value);
        }
        $user = $request->user();
        if(empty($user)){
            $user = User::find($input['user_id']);
        }
        try{
            DB::beginTransaction();

            $create = Order::create([
                'code' => $input['code'],
                'user_id' => $input['user_id'],
                'user_name' => $input['user_name'],
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
                    'color_id' => $value['color_id'],
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
            'message' => 'Đặt hàng thành công !',
            'data' => [
                'order_code' => $create->code,
            ],
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
    public function update(Request $request, $id, OrderUpdateValidator $validator, CancelOrderValidator $cancelValidator, ApproveOrderValidator $approveValidator)
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

            // if($input['status'] == ORDER_STATUS_APPROVED){
            //     if($update->status == ORDER_STATUS_APPROVED){
            //         return response()->json([
            //             'status' => 'error',
            //             'message' => 'Đơn hàng đã được duyệt !'
            //         ], 400);
            //     }
            //     $input['warehouse_id'] = $user->store->warehouse->id ?? $request->warehouse_id ?? null;
            //     $approveValidator->validate($input);
            //     $update->warehouse_id = $input['warehouse_id'];
            // }

            if($input['status'] == ORDER_STATUS_CANCELED){
                if(!in_array($update->status, ORDER_STATUS_CAN_CANCEL)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Đơn hàng đã ở trạng thái không thể hủy !',
                    ], 400);
                }
                $cancelValidator->validate($input);
                $update->cancel_by = $user->id;
            }

            if($input['status'] == ORDER_STATUS_RETURNED){
                $cancelValidator->validate($input);
                $update->cancel_by = $user->id;
            }

            if($input['status'] == ORDER_STATUS_COMPLETED){
                if($update->status == ORDER_STATUS_NEW){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Đơn hàng chưa xác nhận !',
                    ], 400);
                }
                foreach($update->details as $detail){
                    $prod = productAmountByWarehouse::where('product_id', $detail->product_id)->where('pro_variant_id', $detail->variant_id)->where('color_id', $detail->color_id)->where('warehouse_id', $update->warehouse_id)->first();
                    $product = Product::where('id', $detail->product_id)->where('is_active', 1)->first();
                    $variant = ProductVariant::where('id', $detail->variant_id)->where('is_active', 1)->first();
                    $color = Color::where('id', $detail->color_id)->where('is_active', 1)->first();
                    if(empty($prod)){
                        return response()->json([
                            'status' => 'error',
                            'message' => '['.$product->name.'] Không tồn tại biến thể ['.$variant->variant_name.'] ['.$color->name.'] !',
                        ], 404);
                    }
                    if(!empty($prod) && $prod->product_amount > $detail->quantity){
                        $prod->product_amount = $prod->product_amount - $detail->quantity;
                        $prod->save();
                    }
                    else{
                        return response()->json([
                            'status' => 'error',
                            'message' => '['.$product->name.'] ['.$variant->variant_name.'] ['.$color->name.'] Số lượng còn lại trong kho ['.$update->warehouse->name.'] không đủ !'
                        ], 400);
                    }
                }
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

    public function clientCancelOrder(Request $request, ClientCancelOrderValidator $validator){
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();

        try{
            DB::beginTransaction();
            $data = Order::where('code', $input['order_code'])->whereIn('status', ORDER_STATUS_CAN_CANCEL)->first();

            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng đã ở trạng thái không thể hủy hoặc đã hủy !',
                ], 400);
            }

            if($user->id !== $data->user_id){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn chỉ có thể hủy những đơn do bạn đặt !',
                ], 400);
            }

            $data->cancel_reason = $input['cancel_reason'];
            $data->status = ORDER_STATUS_CANCELED;
            $data->cancel_by = $user->id;
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
            'message' => 'Đã hủy đơn hàng !'
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
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
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

    public function approveOrder($code, Request $request, ApproveOrderValidator $validator){
        $input = $request->all();
        $user = $request->user();
        $input['warehouse_id'] = $user->store->warehouse->id ?? $request->warehouse_id ?? null;
        $validator->validate($input);
        try{
            DB::beginTransaction();

            $order = Order::where('code', $code)->first();
            if(empty($order)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng không tồn tại !',
                ], 404);
            }
            foreach($order->details as $detail){
                $prod = productAmountByWarehouse::where('product_id', $detail->product_id)->where('pro_variant_id', $detail->variant_id)->where('color_id', $detail->color_id)->where('warehouse_id', $input['warehouse_id'])->first();
                $warehouse = Warehouse::find($input['warehouse_id']);
                $product = Product::where('id', $detail->product_id)->where('is_active', 1)->first();
                $variant = ProductVariant::where('id', $detail->variant_id)->where('is_active', 1)->first();
                $color = Color::where('id', $detail->color_id)->where('is_active', 1)->first();

                if(empty($prod) || $prod->product_amount < $detail->quantity){
                    return response()->json([
                        'status' => 'error',
                        'message' => '['.$product->name.'] ['.$variant->variant_name.'] ['.$color->name.'] Số lượng còn lại trong kho ['.$warehouse->name.'] không đủ !',
                    ], 400);
                }
            }

            if($order->status == ORDER_STATUS_NEW){
                $order->status = ORDER_STATUS_APPROVED;
                $order->warehouse_id = $input['warehouse_id'];
                $order->updated_by = $user->id;
                $order->save();
            }
            else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đơn hàng đã ở trạng thái xác nhận !',
                ], 400);
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
            'message' => 'Đã xác nhận đơn hàng ['.$order->code.']',
        ]);
    }


    public function exportOrdeBillr($order_code){
        $order = Order::where('code', $order_code)->first();
        if(empty($order)){
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy đơn hàng !',
            ], 404);
        }
        $pdf = Pdf::loadView('Export/export_bill', ['data' => $order]);
        return $pdf->download('Bill_'.$order->code.'.pdf');
    }
}
