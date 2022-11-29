<?php

namespace App\Http\Controllers;

use App\Models\StoreRules;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class StoreRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data= StoreRules::all();
            //$resource = PostResource::collection($data);
            return response()->json([
               'data'=>$data,
            ],200);

          } catch(HttpException $e){
           return response()->json([
               'status' => 'Error',
               'message' => $e->getMessage()
            ],400);
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
            'title' => 'required|max:50',
            'content'=> 'required',
        ];
        $messages = [
            'title.required' => ':atribuite không được để trống !',
            'name.required' => ':attribute không được để trống!',
        ];
        $attributes=[
            'title'=>'Tiêu đề chính sách',
            'slug'=>'Slug',
            'content'=>'Nội dung chính sách',
        ];
        try {
            $user = auth('sanctum')->user();
            DB::beginTransaction();
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

            $data = StoreRules::create([
                'title' => mb_strtoupper($request->title) ,
                'slug' => StR::slug($request->title),
                'content'=>$request->content,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            DB::commit();


        } catch(HttpException $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 400);

        }

        return response()->json([
            'status' => 'success',
            'message' => $data->title. ' đã được tạo thành công !',
        ]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreRules  $StoreRules
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $data = StoreRules::find($id);

            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => ' Không tồn tại, vui lòng kiểm tra lại'

                ],400);
            }

            return response()->json([
                //  'data' => $data->post,
                 'data' => $data
            ],200);

        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreRules  $StoreRules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|max:50',
            'content'=> 'required',
        ];
        $messages = [
            'title.required' => ':atribuite không được để trống !',
            'name.required' => ':attribute không được để trống!',
        ];
        $attributes=[
            'title'=>'Tiêu đề chính sách',
            'slug'=>'Slug',
            'content'=>'Nội dung chính sách',
        ];
        try {
            $user = auth('sanctum')->user();
            DB::beginTransaction();
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $data = StoreRules::find($id);
            if(!empty($data)){
                 $data->update([
                    'title' => mb_strtoupper($request->title) ,
                    'slug' => Str::slug($request->title),
                    'content'=>$request->content,
                    'updated_by' => $user->id,
                ]);
            }

        } catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);

        }
        return response()->json([
            'status' => 'success',
            'message' =>'Nội dung cửa hàng đã được cập nhật thành !',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreRules  $StoreRules
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = StoreRules::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tồn tại !',
                ], 404);

            }
           $data->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa thành công ' . $data->title .'!'
            ]);

            $data->update([
                // 'is_delete' => 1,
                'is_delete' => 1,
                'deleted_at' => Carbon::now()
            ]);
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
