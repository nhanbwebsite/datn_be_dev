<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Validators\Cart\CartCreateValidator;
use App\Http\Validators\Cart\CartDetailUpsertValidator;
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
                    'discount' => 0
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
    public function update(Request $request)
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
