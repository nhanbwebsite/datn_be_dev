<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Validators\Order\OrderCreateValidator;
use App\Http\Validators\Order\OrderDetailCreateValidator;
use App\Models\AddressNote;
use App\Models\Order;
use App\Models\OrderDetail;
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
            $data = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('address_notes', 'orders.address_note_id', '=', 'address_notes.id')
            ->join('users', 'address_notes.user_id', '=', 'users.id')
            ->select('orders.*')
            ->where(function ($query) use($input){
                if(!empty($input['code'])){
                    $query->where('orders.code', $input['code']);
                }
                if(!empty($input['status'])){
                    $query->where('orders.status', $input['status']);
                }
                if(!empty($input['product_id'])){
                    $query->where('order_details.product_id', $input['product_id']);
                }
                if(!empty($input['user_id'])){
                    $query->where('address_notes.user_id', $input['user_id']);
                }
                if(!empty($input['phone'])){
                    $query->where('address_notes.phone', $input['phone']);
                }
                if(!empty($input['email'])){
                    $query->where('address_notes.email', $input['email']);
                }
                if(!empty($input['role_id'])){
                    $query->where('users.role_id', $input['role_id']);
                }
            })->groupBy('order_details.order_id')->orderBy('orders.created_at', 'desc')->paginate($input['limit']);
            // dd($data);
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
        $input['total'] = 0;
        $orderCreateValidator->validate($input);
        foreach($input['details'] as $key => $value){
            $orderDetailCreateValidator->validate($value);
            $input['total'] += $value['price'];
        }
        $user = $request->user();
        try{
            DB::beginTransaction();
            $checkAddress = AddressNote::where('is_active', 1)->where('id', $input['address_note_id'])->where('user_id', $user->id)->first();
            if(empty($checkAddress)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Địa chỉ thanh toán không tồn tại !',
                ], 400);
            }
            $create = Order::create([
                'code' => 'DH'.date('dmYHis', time()),
                'address_note_id' => $input['address_note_id'],
                'total' => $input['total'],
                'discount' => $input['discount'] ?? 0,
                'coupon_id' => $input['coupon_id'] ?? null,
                'promotion_id' => $input['promotion_id'] ?? null,
                'fee_ship' => $input['fee_ship'] ?? 0,
                'payment_method_id' => $input['payment_method_id'],
                'shipping_method_id' => $input['shipping_method_id'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            foreach($input['details'] as $key => $value){
                OrderDetail::create([
                    'order_id' => $create->id,
                    'product_id' => $value['product_id'],
                    'quantity' => $value['quantity'],
                    'price' => $value['price'],
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
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
