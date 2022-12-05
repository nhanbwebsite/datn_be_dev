<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Validators\Cart\CartCreateValidator;
use App\Http\Validators\Cart\CartDetailUpsertValidator;
use App\Http\Validators\Cart\CartUpdateValidator;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Coupon;
use App\Models\CouponOrder;
use App\Models\Product;
use App\Models\ProductVariantDetail;
use App\Models\ProductVariantDetailById;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartController extends Controller
{
    /**
     * Display the specified resource.
     *
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

    public function addToCart(Request $request, CartCreateValidator $validator){
        $user = $request->user();
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $product = Product::find($input['product_id']);
            if(empty($product)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm !'
                ], 404);
            }
            $variant = ProductVariantDetail::where('variant_id', $input['variant_id'])->where('product_id', $input['product_id'])->first();
            if(empty($variant)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy biến thể sản phẩm !'
                ], 404);
            }

            $variantDetail = ProductVariantDetailById::where('pro_variant_id', $variant->id)->where('color_id', $input['color_id'])->where('is_active', 1)->first();

            if(empty($variantDetail)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tồn tại chi tiết biến thể sản phẩm !',
                ], 404);
            }

            $discountPrice = 0;
            if(!empty($variantDetail->discount)){
                $discountPrice += $variantDetail->discount * $input['quantity'];
            }
            $cart = Cart::where('user_id', $user->id)->whereNull('deleted_at')->first();
            if(empty($cart)){
                $cartCreate = Cart::create([
                    'user_id' => $user->id,
                    'address' => $user->address ?? null,
                    'ward_id' => $user->ward_id,
                    'district_id' => $user->district_id,
                    'province_id' => $user->province_id,
                    'phone' => $user->phone,
                    'email' => $user->email ?? null,
                    'fee_ship' => 18000,
                    'discount' => $discountPrice > 0 ? $discountPrice : 0,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                CartDetail::create([
                    'cart_id' => $cartCreate->id,
                    'product_id' => $input['product_id'],
                    'variant_id' => $input['variant_id'],
                    'color_id' => $input['color_id'],
                    'price' => empty($variantDetail->discount) || $variantDetail->discount == 0 ? $variantDetail->price : $variantDetail->price - $variantDetail->discount,
                    'quantity' => $input['quantity'] ?? 1,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
                $message = 'Đã thêm sản phẩm vào giỏ hàng !';
            }
            else {
                $check = CartDetail::where('product_id', $input['product_id'])->where('variant_id', $input['variant_id'])->where('color_id', $input['color_id'])->first();
                if(empty($check)){
                    $validator->validate($input);

                    $cart->discount += $discountPrice;
                    $cart->save();
                    CartDetail::create([
                        'cart_id' => $cart->id,
                        'product_id' => $input['product_id'],
                        'variant_id' => $input['variant_id'],
                        'color_id' => $input['color_id'],
                        'price' => $variantDetail->discount == 0 ? $variantDetail->price : $variantDetail->price - $variantDetail->discount,
                        'quantity' => $input['quantity'] ?? 1,
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);
                    $message = 'Đã thêm sản phẩm vào giỏ hàng !';
                }
                else{
                    $validator->validate($input);

                    $cart->discount += $discountPrice * ($input['quantity'] ?? 1);
                    $cart->save();
                    $check->quantity += $input['quantity'] ?? 1;
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
        $user = $request->user();
        $validator->validate($input);

        try{
            DB::beginTransaction();

            $discountPrice = 0;

            if(!empty($input['coupon_id'])){
                $checkIsset = Coupon::where('id', $input['coupon_id'])->where('is_active', 1)->first();
                if(!empty($checkIsset)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Mã giảm giá không tồn tại !',
                    ], 404);
                }
                $checkMaxUse = CouponOrder::where('coupon_id', $input['coupon_id'])->count();
                if($checkIsset->max_use < $checkMaxUse){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Mã giảm giá đã hết lượt sử dụng !',
                    ], 400);
                }

                foreach($user->order as $ord){
                    $checkUsed = CouponOrder::where('order_id', $ord->id)->first();
                    if(empty($checkUsed)){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Bạn đã hết lượt sử dụng mã giảm giá !',
                        ], 400);
                    }
                }

                $discountPrice += $checkIsset->discount_value;
            }

            $data = Cart::where('user_id', $user->id)->whereNull('deleted_at')->first();
            if(empty($data)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Giỏ hàng không tồn tại !'
                ], 404);
            }

            if(!empty($input['details'])){
                foreach($input['details'] as $d){
                    $variant = ProductVariantDetail::where('variant_id', $d['variant_id'])->where('product_id', $d['product_id'])->first();
                    $variantFind = ProductVariantDetailById::where('pro_variant_id', $variant->id)->where('color_id', $d['color_id'])->first();
                    if($variantFind->discount > 0){
                        $discountPrice += $variantFind->discount * $d['quantity'];
                    }
                }
            }

            $data->address = $input['address'] ?? $data->address;
            $data->ward_id = $input['ward_id'] ?? $data->ward_id;
            $data->district_id = $input['district_id'] ?? $data->district_id;
            $data->province_id = $input['province_id'] ?? $data->province_id;
            $data->phone = $input['phone'] ?? $data->phone;
            $data->email = $input['email'] ?? $data->email;
            $data->coupon_id = $input['coupon_id'] ?? $data->coupon_id;
            $data->discount = $discountPrice ?? $data->discount;
            $data->fee_ship = $input['fee_ship'] ?? $data->fee_ship;
            $data->shipping_method_id = $input['shipping_method_id'] ?? $data->shipping_method_id;
            $data->payment_method_id = $input['payment_method_id'] ?? $data->payment_method_id;
            $data->updated_by = $user->id;
            $data->save();

            foreach($data->details as $delete){
                $delete->deleted_by = $user->id;
                $delete->save();
                $delete->delete();
            }

            if(!empty($input['details'])){
                foreach($input['details'] as $item){
                    $detailValidator->validate($item);
                    $variant2 = ProductVariantDetail::where('variant_id', $item['variant_id'])->where('product_id', $item['product_id'])->first();
                    $variantFind2 = ProductVariantDetailById::where('pro_variant_id', $variant2->id)->where('color_id', $item['color_id'])->first();
                    CartDetail::create([
                        'cart_id' => $data->id,
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'],
                        'color_id' => $item['color_id'],
                        'quantity' => $item['quantity'],
                        'price' => $variantFind2->discount > 0 ? $variantFind2->price - $variantFind2->discount : $variantFind2->price,
                    ]);
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

    public function deleteDetail($product_id, $variant_id, $color_id, Request $request){
        $user_id = $request->user()->id;
        try{
            DB::beginTransaction();

            $cart = Cart::where('user_id', $user_id)->whereNull('deleted_at')->first();
            if(empty($cart)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Giỏ hàng không tồn tại !'
                ], 404);
            }

            $data = CartDetail::where('cart_id', $cart->id)->where('product_id', $product_id)->where('variant_id', $variant_id)->where('color_id', $color_id)->first();
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng !'
                ], 404);
            }

            $data->deleted_by = $user_id;
            $data->save();
            $data->delete();

            $discountPrice = 0;
            foreach($cart->details as $key => $value){
                $variant = ProductVariantDetail::where('variant_id', $value->variant_id)->where('product_id', $value->product_id)->first();
                $color = ProductVariantDetailById::where('pro_variant_id', $variant->id)->where('color_id', $value->color_id)->first();
                if($color->discount > 0){
                    $discountPrice += $color->discount * $color->quantity;
                }
            }

            $cart->discount = $discountPrice;
            $cart->save();

            if(count($cart->details) == 0){
                $cart->deleted_by = $user_id;
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
                    'message' => 'Giỏ hàng không tồn tại !'
                ], 404);
            }
            foreach($data as $detail){
                $detail->deleted_by = $user_id;
                $detail->delete();
            }

            $cart->deleted_by = $user_id;
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
