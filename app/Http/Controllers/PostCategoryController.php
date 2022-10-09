<?php

namespace App\Http\Controllers;

use App\Models\PostCategories;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $dataCategories = PostCategories::all();
            return response()->json([
                'data' => $dataCategories
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
            'name_post_category' => 'required|max:255',
        ];
        $messages = [
            'name_post_category.required' => ':atribuite không được để trống !',
            'name_post_category.max' => ':attribute tối đa 255 ký tự !',
        ];

        $attributes = [
            'name_post_category' => 'Tên danh mục không được để trống'
        ];

        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

            $data = PostCategories::create([
                'name_post_category' => $request->name_post_category,

            ]);
            DB::commit();


        } catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);

        }

        return response()->json([
            'status' => 'success',
            'message' => $data->name_post_category . ' đã được tạo thành công !',
        ]);

        // $data = $request->all();
        // PostCategories::create($data);
        // echo "thêm thành công";

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\post_category  $post_category
     * @return \Illuminate\Http\Response
     */
    public function show(PostCategories $PostCategories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\post_category  $post_category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostCategories $PostCategories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\post_category  $post_category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data= PostCategories::find($id);
        $data->delete($id);
        return response()->json([
                    'status' => 'success',
                    'message' => 'Đã xóa thành công danh mục ' . $data->name_post_category
                 ]);

    }
}
