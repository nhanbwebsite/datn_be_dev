<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try{
         $data= Post::orderBy('created_at','desc')->simplePaginate(10);
         $resource = PostResource::collection($data);
         return response()->json([
            'data'=>$resource,
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
        $rules=[
            'category_id'=>'required',
            // 'user_id'=>'required',
            'title'=>'required|max:255|unique:posts',
            'short_des'=>'required|max:255',
            'content_post'=>'required',
            // 'image'=>'required|image',
            'meta_title'=>'required|max:120',
            'meta_keywords'=>'required|max:255',
        ];
        $messages = [
            'category_id.required' => ':atribuite không được để trống !',
            'user_id.required' => ':atribuite không được để trống !',
            'title.required' => ':atribuite không được để trống !',
            'title.max' => ':atribuite đã vượt qua độ dài cho phép !',
            'short_des.required' => ':atribuite không được để trống !',
            'short_des.max' => ':atribuite đã vượt qua độ dài cho phép !',
            'content_post.required'=> ':atribuite không được để trống !',
            'image..required' => ':atribuite không được để trống !',
            'image.image' => 'atribuite phải là định dạng hình ảnh',
            'meta_title.required' => ':atribuite không được để trống !',
            'meta_title.max' => ':atribuite đã vượt qua độ dài cho phép !',
            'meta_keywords.required'=> ':atribuite không được để trống !',
            'meta_keywords.max'=> ':atribuite đã vượt qua độ dài cho phép !',
        ];
        $atribuite =[
            'category_id'=>'Id danh mục bài viết',
            'user_id'=>'Id người đăng bài',
            'title'=>'Tiêu đề của bài viết',
            'short_des'=>'Mô tả ngắn của bài viết',
            'content_post'=>'Nội dung bài viết',
            'image'=>'Ảnh đại diện bài viết',
            'meta_title'=>'Thẻ tiêu đề bài viết',
            'meta_keywords'=>'Thẻ từ khóa bài viết',
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), $rules, $messages, $atribuite);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $data = Post::create([
                'category_id'=>$request->category_id,
                // 'user_id'=> $request->user_id,
                'title'=> mb_strtoupper($request->title),
                'short_des'=> mb_strtoupper(mb_substr($request->short_des, 0, 1)).mb_substr($request->short_des, 1),
                'content_post'=> $request->content_post,
                //'image'=> $request->image,
                'meta_title'=>$request->meta_title,
                'meta_keywords'=>$request->meta_keywords,
                'slug' => Str::slug($request->title),

            ]);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response()->json([
            'data'=>$data,
            'status' => 'success',
            'message' => $data->title . ' đã được tạo thành công !',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Post::find($id)->first();
        $data->views = $data->views + 1;
        $data->save();
        // Event::fire('posts.view', $data);
        if($data) {
            $resource = new PostResource($data);
            return response()->json([
                'data' => $resource,
                'status' => true,
                'message' => 'Get data success'
            ]);
        } else {
            return response()->json([
                'data' => [],
                'status' => false,
                'message' => 'id not found'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'category_id'=>'required',
            'user_id'=>'required',
            'title'=>'required|max:255',
            'short_des'=>'required|max:255',
            'content_post'=>'required',
            'image'=>'required|image',
            'meta_title'=>'required|max:120',
            'meta_keywords'=>'required|max:255',
        ];
        $messages = [
            'category_id.required' => ':atribuite không được để trống !',
            'user_id.required' => ':atribuite không được để trống !',
            'title.required' => ':atribuite không được để trống !',
            'title.max' => ':atribuite đã vượt qua độ dài cho phép !',
            'short_des.required' => ':atribuite không được để trống !',
            'short_des.max' => ':atribuite đã vượt qua độ dài cho phép !',
            'content_post.required'=> ':atribuite không được để trống !',
            'image..required' => ':atribuite không được để trống !',
            'image.image' => 'atribuite phải là định dạng hình ảnh',
            'meta_title.required' => ':atribuite không được để trống !',
            'meta_title.max' => ':atribuite đã vượt qua độ dài cho phép !',
            'meta_keywords.required'=> ':atribuite không được để trống !',
            'meta_keywords.max'=> ':atribuite đã vượt qua độ dài cho phép !',
        ];
        $attributes =[
            'category_id'=>'Id danh mục bài viết',
            'user_id'=>'Id người đăng bài',
            'title'=>'Tiêu đề của bài viết',
            'short_des'=>'Mô tả ngắn của bài viết',
            'content_post'=>'Nội dung bài viết',
            'image'=>'Ảnh đại diện bài viết',
            'meta_title'=>'Thẻ tiêu đề bài viết',
            'meta_keywords'=>'Thẻ từ khóa bài viết',
        ];

        try {
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $data = Post::find($id);
            if(!empty($data)){
                 $data->update([
                'category_id'=>$request->category_id,
                'user_id'=> $request->user_id,
                'title'=> mb_strtoupper($request->title),
                'short_des'=> mb_strtoupper(mb_substr($request->short_des, 0, 1)).mb_substr($request->short_des, 1),
                'content_post'=> $request->content_post,
                'image'=> $request->image,
                'meta_title'=>$request->meta_title,
                'meta_keywords'=>$request->meta_keywords,
                'slug' => Str::slug($request->title),
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
            'message' =>'Danh mục đã được cập nhật thành !',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Post::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại !',
                ], 404);

            }
           $data->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa thành công bài viết' . $data->title .'!'
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

    public function loadByViews(){
        {
            try{
              $data= Post::orderBy('views','desc')->paginate(9);
              $resource = PostResource::collection($data);
              return response()->json([
                 'data'=>$resource,
              ],200);

            } catch(Exception $e){
             return response()->json([
                 'status' => 'Error',
                 'message' => $e->getMessage()
              ],400);
            }
         }
    }
}
