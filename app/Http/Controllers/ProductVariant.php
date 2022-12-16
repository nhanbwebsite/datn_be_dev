<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductVariant as ProductVariantModel;
use App\Models\ProductVariantDetailById;
class ProductVariant extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // không phân trang
        $data = ProductVariantModel::all();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ],200);
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
            'variant_name' => 'required',

        ];

        $messages = [
            'variant_name.required' => ':attribute không được bỏ trống',
        ];

        $attributes = [
            'variant_name' => 'Tên biến thể',
        ];

        try {
            DB::beginTransaction();
            $user = auth('sanctum')->user();
            $validator = Validator::make($request->only('variant_name'), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

          $create =   ProductVariantModel::create([
                'variant_name' => $request->variant_name,
                'slug' => Str::slug($request->variant_name),
                'created_by' => $user->id
            ]);
            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ],$e->getStatusCode()); //
        }
        return response()->json([
            'status' => 'Created successfully',
            'data =>' => $create
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
            $data = ProductVariantModel::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Biến thể không tồn tại !',
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
            'data' => $data,
        ]);
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
        $user = auth('sanctum')->user();
        $rules = [
            'variant_name' => 'required',
        ];

        $messages = [
            'variant_name.required' => ':attribute không được để trống !'
        ];

        $attributes = [
            'variant_name' => 'Tên biến thể'
        ];
        $validator = Validator::make($request->only('variant_name'), $rules, $messages, $attributes);
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }
        try {
            DB::beginTransaction();
            $data = ProductVariantModel::find($id);
            $data->variant_name = $request->variant_name;
            $data->slug = Str::slug($request->variant_name);
            $data->is_active = $request->is_active;
            $data->updated_by = $user->id;
            $data->save();
            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ],$e->getStatusCode()); //
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
        $user = auth('sanctum')->user();
        try {
            DB::beginTransaction();
            $data = ProductVariantModel::find($id);

            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy biến thể !'
                ], 404);
            }

            $data->delete_by = $user->id;
            $data->save();
            $data->delete();
            DB::commit();
        } catch(HttpException $e){
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
            'message' => 'Đã xóa ['.$data->variant_name.'] !',
        ]);
    }
}
