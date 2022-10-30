<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Product::paginate(9);
            if($data){
                if(count($data) > 0){
                    return response()->json([
                        'data' => $data
                       ],200);
                } else{
                    return response()->json([
                        'message' => 'Danh sách sản phẩn trống, vui lòng thêm sản phẩm vào danh sách !'
                       ],200);
                }
            }

        } catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:6|max:255',
            'brand_id' => 'required',
            'meta_description' => 'required',
            // 'store_id' => 'required'
        ];
        $messages = [
            'name.required' => ':attribute không được để trống !',
            'name.min' => ':attribute tối thiểu 6 ký tự !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'brand_id.required' => ':attribute không được để trống !',
            'meta_description.required' => ':attribute không được để trống !',
            'store_id.required' => ':attribute không được để trống !'
        ];
        $attributes = [
            'name' => 'Tên sản phẩm',
            'brand_id' => 'Tên thương hiệu',
            'meta_description' => 'meta_description',
            'store_id.required' => 'Cửa hàng'
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['name','brand_id','meta_description','store_id']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            // dd(auth('sanctum')->user()->store_id);
            $create = Product::create([
                'meta_title' => $request->meta_description,
                'meta_keywords'=>$request->meta_keywords,
                'meta_description' => $request->meta_description,
                'name' => $request->name,
                'slug' =>   Str::slug($request->name),
                'description' => $request->description,
                'url_image'=>  $request->url_image,
                'price' =>  $request->price,
                'promotion' =>  $request->promotion,
                // 'color_ids' =>  $request->color_ids,
                // 'product_weight' =>  $request->product_weight,
                // 'product_height' =>  $request->product_height,
                // 'product_width' =>  $request->product_width,
                'brand_id' => $request->brand_id,
                'store_id'=>  auth('sanctum')->user()->store_id,
                'subcategories_id' => $request->subcategories_id,

            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
             ],400);
        }

        return response()->json([
            'status' => 'success',
            'data' => $create
        ],200);
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
            DB::beginTransaction();
            $data = Product::find($id);

            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm !',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
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
        $rules = [
            'name' => 'required|min:6|max:255',
            'brand_id' => 'required',
        ];
        $messages = [
            'name.required' => ':attribute không được để trống !',
            'name.min' => ':attribute tối thiểu 6 ký tự !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'brand_id.required' => ':attribute không được để trống !',
        ];
        $attributes = [
            'name' => 'Tên sản phẩm',
            'brand_id' => 'Tên thương hiệu',
            'branch_id' => 'Tên chi nhánh'
        ];
        try {

            $validator = Validator::make($request->only(['name','brand_id']), $rules, $messages, $attributes);
                if($validator->fails()){
                    return response()->json([
                        'status' => 'error',
                        'message' => $validator->errors(),
                    ], 422);
                }

               $product = Product::find($id);

               if($product){
                    $product->update([
                        'meta_title' => $request->meta_title,
                        'meta_keywords' =>  $request->meta_keywords,
                        'meta_description' => $request-> meta_description,
                        'name' =>  $request -> name,
                        'slug' =>  Str::slug($request->name),
                        'description' => $request->description,
                        'url_image' =>  $request->url_image,
                        'price'=>  $request->price,
                        'promotion' =>  $request->promotion,
                        // 'color_ids' =>  $request->color_ids,
                        // 'product_weight' =>  $request->product_weight,
                        // 'product_height'  => $request->product_height,
                        // 'product_width'  => $request->product_width,
                        'deleted_by' =>  $request->deleted_by,
                        'brand_id' =>  $request->brand_id,
                        'branch_id'  => $request->branch_id,
                        'subcategories_id'  => $request->subcategories_id,
                        'is_active' =>  $request->is_active
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => $product['name'] . ' đã được cập nhật'
                    ]);
               }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            // DB::beginTransaction();
            $data = Product::find($id);

            if(!empty($data)){
                // $data->update([
                //     'is_delete' => 1,
                //     'delete_by' => auth('sanctum')->user()->id
                // ]);
                $data->is_delete = 1;
                $data->deleted_by = auth('sanctum')->user()->id;
                $data->save();

                $delete = $data->delete();

                if($delete) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Sản phẩm ' . $data->id . ' đã được xóa'
                    ],200);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Lỗi, vui lòng thử lại !'
                ],400);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm !'
            ],400);

            // DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }
}
