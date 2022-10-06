<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
// use Illuminate\Support\Facades\DB;
class Subcategories extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = SubCategory::orderBy('id','DESC')->paginate(9);
            return response()->json([
                'message' => 'SubCategories',
                'data' => $data
            ],200);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
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
        try {
            DB::beginTransaction();
            $rules = [
                'category_id' => 'required',
                'name' => 'required|max:255'
            ];

            $messages = [
                'category_id.required' => ':atribuite không được để trống !',
                'name.required' => ':attribute không được để trống !',
                'name.max' => ':attribute tối đa 255 ký tự !'
            ];

            $attributes = [
                'category_id' => 'Danh mục cha không được để trống',
                'Tên Sub danh mục không được để trống'
            ];

            $validator = Validator::make($request->all(),$rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ],400);
            }

            $create = Category::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return response()-> json([
                'status' => 'error',
                'message' => 'Categories created ' . $create->name
            ],200);

            DB::commit();
        } catch(Exception $e) {

            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],400);

        }

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
            $dataSubcategory = SubCategory::find($id);
            if($dataSubcategory){
                return response()->json([
                    'message' => 'List subcategories',
                    'data' => $dataSubcategory
                ],200);
            }
            return response()->json([
                'status' => ' error',
                'message' => 'Danh mục không tồn tại !'
            ]); //

        } catch(Exception $e) {
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
            'category_id' => 'required',
            'name' => 'required|max:255'
        ];

        $messages = [
            'category_id.required' => ':atribuite không được để trống !',
            'name.required' => ':attribute không được để trống !',
            'name.max' => ':attribute tối đa 255 ký tự !'
        ];

        $attributes = [
            'category_id' => 'Danh mục cha không được để trống',
            'Tên Sub danh mục không được để trống'
        ];



        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(),$rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ],400);
            }

            $subcategory = SubCategory::find($id);
            $subcategory->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật thành công  danh mục ' . $subcategory->name
            ]);

        }catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
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
            DB::beginTransaction();
            $dataDelete = SubCategory::find($id);
            if(!empty($dataDelete)){

                $dataDelete->update([
                    'is_delete' => 1,
                    'updated_by' => auth('sanctum')->user()->id,
                ]);

                $dataDelete->delete();

                return response()->json([
                   'message' => 'deleted subcategory successfully',
                   'data' => $dataDelete
                ],200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Danh mục '. "[$dataDelete->name]" . ' không tồn tại !'
            ]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
        ]);
        }

    }
}
