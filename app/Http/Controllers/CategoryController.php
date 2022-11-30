<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryClientCollection;
use App\Http\Resources\CategoryCollection;
use Illuminate\Http\Request;
use App\Models\Category;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\SubCategory;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = !empty($input['limit']) ? $input['limit'] : 10;
        try{
            $data = Category::where('is_active', $input['is_active'] ?? 1)
            ->where(function($query) use ($input){
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['slug'])){
                    $query->where('slug', $input['slug']);
                }
            })->orderBy('created_at', 'desc')->paginate($input['limit']);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json(new CategoryCollection($data));
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
            'name' => 'Tên danh mục không được để trống'
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
            if(!empty($request->is_post)){
                $categoryCreate = Category::create([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'is_post' => 1
                ]);
            } else {
                $categoryCreate = Category::create([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name)
                ]);
            }

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
            'message' => $categoryCreate->name . ' đã được tạo thành công !',
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
            $category = Category::find($id);
            if(!empty($category)){
                return response()->json([
                    'data' => $category
                ],200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Danh mục không tồn tại, vui lòng kiểm tra lại'
            ],400);

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
     * @param  int  $id
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
            DB::beginTransaction();
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $category = Category::find($id);
            if(!empty($category)){
                $categoryCreate = $category->update([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'url_img' => $request->url_img,
                    // 'updated_by' => auth('sanctum')->user()->id,
                ]);
            }
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
            'message' =>'Danh mục '. $request->name . ' đã được cập nhật !',
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

            $data = Category::find($id);

            if(!empty($data)){
                $data->deleted_by = auth('sanctum')->user()->id;
                $data->save();
               $data->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đã xóa thành công danh mục ' . $data->name
                ]);


            }

            return response()->json([
                'status' => 'error',
                'message' => 'Danh mục không tồn tại !',
            ], 404);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getClientCategory(){
        try{
            $data = Category::where('is_active', 1)->get();
            foreach( $data as $value){
                // push object subscategories
                $value->subs = Category::subByCategoryID($value->id);
            }
        } catch(Exception $e){
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
            'status'=>'success',
            'data' =>$data
        ]);
    }
    public function getCategoryProducts(){
        try{
            $data = Category::where('is_active', 1)
            ->where('is_post',0)
            ->get();
            foreach( $data as $value){
                // push object subscategories
                $value->subs = Category::subByCategoryID($value->id);
            }
        } catch(Exception $e){
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
            'status'=>'success',
            'data' =>$data
        ]);
    }
    public function getCategory_is_post(){
        try{
            $data = Category::where('is_active', 1)
            ->where('is_post',1)
            ->get();
            foreach( $data as $value){
                // push object subscategories
                $value->subs = Category::subByCategoryID($value->id);
            }
        } catch(Exception $e){
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
            'status'=>'success',
            'data' =>$data
        ]);
    }

}
