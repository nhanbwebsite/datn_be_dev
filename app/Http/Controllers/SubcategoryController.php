<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoadPostByCateResouce;
use App\Models\Category;
use App\Models\SubCategory;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Resources\subcateGoryCollection;
use App\Models\Post;
use App\Models\Product;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = SubCategory::paginate(9);
            return response()->json([
                'message' => 'SubCategories',
                'data' => new subcateGoryCollection($data)
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
                'name' => 'required|max:255',
                'brand_id' => 'required',
            ];

            $messages = [
                'category_id.required' => ':atribuite không được để trống !',
                'name.required' => ':attribute không được để trống !',
                'name.max' => ':attribute tối đa 255 ký tự !',
                'brand_id.required' => ':attribute không được để trống !'
            ];

            $attributes = [
                'category_id' => 'Danh mục cha',
                'name' => 'Tên danh mục con',
                'brand_id' => 'Tên thương hiệu'
            ];

            $validator = Validator::make($request->all(),$rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ],400);
            }

           if(!empty($request->brand_id)){
            $create = SubCategory::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'brand_id' => $request->brand_id,
                'is_post' => $request->is_post ?? null,
                'created_by' => auth('sanctum')->user()->id,
                'updated_by' => auth('sanctum')->user()->id,
            ]);
           } else{
            $create = SubCategory::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'is_post' => $request->is_post ?? null,
                'created_by' => auth('sanctum')->user()->id,
                'updated_by' => auth('sanctum')->user()->id,
            ]);
           }

            DB::commit();
        } catch(Exception $e) {

            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],400);

        }
        return response()-> json([
            'status' => 'error',
            'message' => 'Subcategory created ' . $create->name
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
            'name' => 'required|max:255',
            'brand_id' => 'required',
        ];

        $messages = [
            'category_id.required' => ':atribuite không được để trống !',
            'name.required' => ':attribute không được để trống !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'brand_id.required' => ':attribute không được để trống !'
        ];

        $attributes = [
            'category_id' => 'Danh mục cha',
            'name' => 'Tên danh mục con',
            'brand_id' => 'Tên thương hiệu'
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
            if(!empty($request->brand_id)){
                $subcategory->update([
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'brand_id' => $request->brand_id,
                ]);
            } else{
                $subcategory->update([
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                ]);
            }

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

            $data = SubCategory::find($id);
            $dataCheck = Product::where('subcategory_id',$data->id)->count();
            $dataCheckPost = Post::Where('subcategory_id',$data->id)->count();
            if(!empty($data)){
                if(  $dataCheck > 0){
                    return response()->json([
                        'message' => 'Danh mục này đã tồn tại sản phẩm, không thể xóa !',
                     ],200);
                }
                if(  $dataCheckPost > 0){
                    return response()->json([
                        'message' => 'Danh mục này đã tồn tại bài viết, không thể xóa !',
                     ],200);
                }
                $data->deleted_by = auth('sanctum')->user()->id;
                $data->save();
                $data->delete();

                return response()->json([
                   'message' => 'xóa thành công',
                   'data' => $data
                ],200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Danh mục '. "[$data->name]" . ' không tồn tại !'
            ]);


        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
        ]);
        }

    }
    public function test($id){
        $name = Category::find($id)->phone;
        return response()->json([
            'data' =>   $name
        ]);
    }

    public function getSubcateClients(){
        try {
            $data = SubCategory::all();
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
        return response()->json([
            'data' =>   $data
        ]);

    }

    public function loadByCate($id)
    {
        try{
            $data = SubCategory::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Danh mục không tồn tại, vui lòng kiểm tra lại'
                ], 404);
            }
        } catch(HttpException $e){
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
            'data' => new LoadPostByCateResouce($data),
        ]);
    }

    public function getFirtsNewPostByCate($id)
    {
        try{
            $data = SubCategory::where('is_active', 1)
            ->where('id',$id)
            ->get();
            foreach( $data as $value){
                // push object contact
                $value->posts = SubCategory::firtsNewPostByCategoryID($value->id);
            }
        }  catch(HttpException $e){
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

    public function getTwoPostAfterNew($id)
    {
        try{
            $data = SubCategory::where('is_active', 1)
            ->where('id',$id)
            ->get();
            foreach( $data as $value){
                // push object contact
                $value->posts = SubCategory::twoPostAfterNewByCategoryID($value->id);
            }
        }  catch(HttpException $e){
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


    public function loadPostByViewOfCate($id)
    {
        try{
            $data = SubCategory::where('is_active', 1)
            ->where('id',$id)
            ->get();
            foreach( $data as $value){
                // push object contact
                $value->posts = SubCategory::postViewByCategoryID($value->id);
            }
        }  catch(HttpException $e){
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


    public function getSubcatePosts(Request $request){
        $input = $request->all();
        $data = SubCategory::where('is_active', 1)
        ->where('is_post',1)
        ->where('deleted_at',null)->where(function ($query) use ($input) {
            if(!empty($input['name'])){
                $query->where('name', 'like', '%'.$input['name'].'%');
            }
            if(!empty($input['slug'])){
                $query->where('slug', 'like', '%'.$input['slug'].'%');
            }
       })->orderBy('created_at','desc')->paginate($input['limit'] ?? 9);

        return response()->json(new subcateGoryCollection($data),200);

    }

    public function getSubcateproducts(Request $request){
        $input = $request->all();
        $data = SubCategory::where('is_active', 1)
        ->where('is_post',null)
        ->where('deleted_at',null)->where(function ($query) use ($input) {
            if(!empty($input['name'])){
                $query->where('name', 'like', '%'.$input['name'].'%');
            }
            if(!empty($input['slug'])){
                $query->where('slug', 'like', '%'.$input['slug'].'%');
            }
       })->orderBy('created_at','desc')->paginate($input['limit'] ?? 9);
       return response()->json(new subcateGoryCollection($data),200);

    }

}
