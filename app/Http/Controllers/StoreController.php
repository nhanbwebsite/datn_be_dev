<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Store::paginate(9);
            return response()->json([
                'data' => $data
            ],200);
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
            'store_name' => 'required',
        ];

        $messages = [
            'store_name.required' =>':attribute không được để trống !'
        ];

        $attributes = [
            'store_name' => 'Tên cửa hàng'
        ];

        try {

            DB::beginTransaction();

            $validator = Validator::make($request->only(['store_name']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' =>'error',
                    'message' => $validator->errors(),
                ],422);

            }

            $create = Store::create([
                'store_name' => $request->store_name,
                'slug' => Str::slug($request->store_name)
            ]);


            DB::commit();

        } catch(Exception $e){
            return response()->json([
                'status' => 'errors',
                'message' => $e->getMessage()
            ],400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm thành công cửa hàng ' ."[$request->store_name]",
            'data' => $create
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
        try {
            $data = Store::where('id',$id)
            // -> where('is_active',1)
            ->first();
            if($data){
                return response()->json([
                    'data' => $data
                ],200);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy cửa hàng phù hợp, vui lòng kiểm tra lại !'
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],400);
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
            'store_name' => 'required',
        ];

        $messages = [
            'store_name.required' =>':attribute không được để trống !'
        ];

        $attributes = [
            'store_name' => 'Tên cửa hàng'
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['store_name']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ],400);
            }

            $update = Store::find($id);
            $update->store_name = $request->store_name;
            $update->slug = Str::slug($request->store_name);
            $update->is_active = $request->is_active;
            $update->save();
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],422);
        }

        return response()->json([
            'status' => 'successfully',
            'data' => $update
        ]);

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
            DB::beginTransaction();
            $data = Store::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cửa hàng không tồn tại, vui lòng kiểm tra lại !',
                ], 404);
            }

            // $data->update([
            //     'is_delete' => 1,
            //     'deleted_by' => auth('sanctum')->user()->id
            // ]);
            $data->is_delete = 1;
            $data->deleted_by = auth('sanctum')->user()->id;
            $data->save();
            $data->delete();
            DB::commit();
        } catch(Exception $e ) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],400);
        }

        return response()->json([
            'status' => 'successfully',
            'message' => 'Đã xóa thành công cửa hàng '. "[$data->store_name]"
        ],200);
    }
}
