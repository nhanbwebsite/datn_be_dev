<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCategoriesResource;
use App\Models\PostCategories;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = PostCategories::all();
            $resource =  PostCategoriesResource::collection($data);
            return response()->json([
                'data' => $resource
            ],200);
        } catch(Exception $e){
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
            'name' => 'required|max:255',
        ];
        $messages = [
            'name.required' => ':atribuite không được để trống !',
            'name.max' => ':attribute tối đa 255 ký tự !',
        ];

        $attributes = [
            'name' => 'Tên danh mục bài viết'
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

            $data = PostCategories::create([
                'name' => mb_strtoupper($request->name) ,
                'slug' => Str::slug($request->name),
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
            'message' => $data->name . ' đã được tạo thành công !',
        ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\post_category  $post_category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $data = PostCategories::find($id);

            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Danh mục không tồn tại, vui lòng kiểm tra lại'

                ],400);
            }

            return response()->json([
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
     * @param  \App\Models\post_category  $post_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
        ];
        $messages = [
            'name.required' => ':atribuite không được để trống !',
            'name.max' => ':attribute tối đa 255 ký tự !',
        ];

        $attributes = [
            'name' => 'Tên danh mục không được để trống'
        ];

        try {
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $data = PostCategories::find($id);
            if(!empty($data)){
                 $data->update([
                    'name' => mb_strtoupper($request->name) ,
                    'slug' => Str::slug($request->name),
                    // 'updated_by' => auth('sanctum')->user()->id,
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
            'message' =>'Danh mục đã được cập nhật thành '.$request->name.'!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostCategories  $post_category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //DB::beginTransaction();
            $data = PostCategories::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Danh mục không tồn tại !',
                ], 404);

            }
           $data->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa thành công danh mục ' . $data->name
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
