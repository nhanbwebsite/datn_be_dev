<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Validators\Cart\CartCreateValidator;
use App\Http\Validators\Cart\CartDetailUpsertValidator;
use App\Http\Validators\Cart\CartUpdateValidator;
use App\Models\AddressNote;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user_id = $request->user()->id;
        try{
            $cart = Cart::where('user_id', $user_id)->whereNull('deleted_at')->first();
            if(empty($cart)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Giỏ hàng không tồn tại !'
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
            'data' => new CartResource($cart)
        ]);
    }

    public function addToCart(Request $request, CartDetailUpsertValidator $detailValidator){
        $user = $request->user();
        $input = $request->all();
        try{
            DB::beginTransaction();
            $product = Product::find($input['product_id']);
            if(empty($product)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm !'
                ], 404);
            }

            $cart = Cart::where('user_id', $user->id)->whereNull('deleted_at')->first();
            if(empty($cart)){
                $detailValidator->validate($input);

                $address = AddressNote::where('user_id', $user->id)->where('is_default', 1)->first();
                $cartCreate = Cart::create([
                    'user_id' => $user->id,
                    'address_note_id' => $address->id,
                    'fee_ship' => 18000,
                    'discount' => 0,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                CartDetail::create([
                    'cart_id' => $cartCreate->id,
                    'product_id' => $input['product_id'],
                    'price' => $product->discount == 0 ? $product->price : $product->price - $product->discount,
                    'quantity' => $input['quantity'],
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
                $message = 'Đã thêm sản phẩm vào giỏ hàng !';
            }
            else {
                $check = CartDetail::where('product_id', $input['product_id'])->first();
                if(empty($check)){
                    $detailValidator->validate($input);

                    CartDetail::create([
                        'cart_id' => $cart->id,
                        'product_id' => $input['product_id'],
                        'price' => $product->discount == 0 ? $product->price : $product->price - $product->discount,
                        'quantity' => $input['quantity'],
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);
                    $message = 'Đã thêm sản phẩm vào giỏ hàng !';

                }
                else{
                    $detailValidator->validate($input);

                    $check->quantity += $input['quantity'];
                    $check->save();
                    $message = 'Đã cập nhật giỏ hàng !';
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
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CartUpdateValidator $validator, CartDetailUpsertValidator $detailValidator)
    {
        $input = $request->all();
        $user_id = $request->user()->id;
        $validator->validate($input);
        try{
            DB::beginTransaction();

            $data = Cart::where('user_id', $user_id)->whereNull('deleted_at')->first();
            if(empty($data)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Giỏ hàng không tồn tại !'
                ], 404);
            }

            $data->address_note_id = $input['address_note_id'] ?? $data->address_note_id;
            $data->coupon_id = $input['coupon_id'] ?? $data->coupon_id;
            $data->promotion_id = $input['promotion_id'] ?? $data->promotion_id;
            $data->discount = $input['discount'] ?? $data->discount;
            $data->fee_ship = $input['fee_ship'] ?? $data->fee_ship;
            $data->updated_by = $user_id;
            $data->save();

            if(!empty($input['details'])){
                foreach($input['details'] as $item){
                    $detailValidator->validate($item);
                    $detailDatas = CartDetail::where('cart_id', $data->id)->where('product_id', $item['product_id'])->whereNull('deleted_at')->first();
                    $detailDatas->quantity = $item['quantity'];
                    $detailDatas->save();
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
            'message' => 'Đã cập nhật giỏ hàng !'
        ]);
    }

    public function deleteDetail($product_id, Request $request){
        $user_id = $request->user()->id;
        try{
            DB::beginTransaction();

            $cart = Cart::where('user_id', $user_id)->whereNull('deleted_at')->first();
            if(empty($cart)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Giỏ hàng không tồn tại !'
                ], 404);
            }

            $data = CartDetail::where('cart_id', $cart->id)->where('product_id', $product_id)->first();
            if(empty($data)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng !'
                ], 404);
            }
            $data->updated_by = $user_id;
            $data->save();
            $data->delete();

            if(count($cart->details) == 0){
                $cart->updated_by = $user_id;
                $cart->delete();
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
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng !',
        ]);
    }

    public function destroyCart(Request $request){
        $user_id = $request->user()->id;
        try{
            DB::beginTransaction();

            $cart = Cart::where('user_id', $user_id)->whereNull('deleted_at')->first();
            if(empty($cart)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Giỏ hàng không tồn tại !'
                ], 404);
            }

            $data = CartDetail::where('cart_id', $cart->id)->whereNull('deleted_at')->get();
            if(empty($data)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng !'
                ], 404);
            }
            foreach($data as $detail){
                $detail->updated_by = $user_id;
                $detail->delete();
            }

            $cart->updated_by = $user_id;
            $cart->save();
            $cart->delete();

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
            'message' => 'Đã xóa giỏ hàng !',
        ]);
    }
}
