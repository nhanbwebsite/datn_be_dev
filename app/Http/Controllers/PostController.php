<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Http\Validators\Post\PostCreateValidator;
use App\Http\Validators\Post\PostUpdateValidator;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit;
        try{
            $data = Post::where('is_active', $input['is_active'] ?? 1)->where(function ($query) use ($input) {
                if(!empty($input['title'])){
                    $query->where('title', 'like', '%'.$input['title'].'%');
                }
                if(!empty($input['slug'])){
                    $query->where('slug', $input['slug']);
                }
                if(!empty($input['user_id'])){
                    $query->where('user_id', $input['user_id']);
                }
                if(!empty($input['subcategory_id'])){
                    $query->where('subcategory_id', $input['subcategory_id']);
                }
            })->orderBy('created_at', 'desc')->paginate($input['limit'] ?? 10);
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
        return response()->json(new PostCollection($data));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PostCreateValidator $validator)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['title']) ?? null;
        $validator->validate($input);
        $user = $request->user();
        try {
            DB::beginTransaction();
            $data = Post::create([
                'subcategory_id'=>$input['subcategory_id'],
                'user_id'=> $user->id,
                'title'=> $input['title'],
                'short_des'=> $input['short_des'],
                'content_post'=> $input['content_post'],
                'image'=> $input['image'] ?? null,
                'meta_title'=>$input['meta_title'] ?? null,
                'meta_keywords'=>$input['meta_keywords'] ?? null,
                'meta_description'=>$input['meta_description'] ?? null,
                'slug' => empty($input['slug']) ? Str::slug($input['title']) : $input['slug'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            DB::commit();
        }catch(HttpException $e){
            DB::rollback();
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
        try{
            DB::beginTransaction();
            $data = Post::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại !'
                ], 404);
            }
            $data->views = $data->views + 1;
            $data->save();
            DB::commit();
        }
        catch(HttpException $e){
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
            'data' => new PostResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PostUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = Post::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại !'
                ], 404);
            }

            $data->subcategory_id = $input['subcategory_id'] ?? $data->subcategory_id;
            $data->title = $input['title'] ?? $data->title;
            $data->updated_by = $user->id;
            $data->short_des = $input['short_des'] ?? $data->short_des;
            $data->content_post = $input['content_post'] ?? $data->content_post;
            $data->image = $input['image'] ?? $data->image;
            $data->meta_title = $input['meta_title'] ?? $data->meta_title;
            $data->meta_keywords = $input['meta_keywords'] ?? $data->meta_keywords;
            $data->meta_description = $input['meta_description'] ?? $data->meta_description;
            $data->slug = Str::slug($input['title']);
            $data->is_active = $input['is_active'] ?? $data->is_active;
            $data->updated_by = $user->id;
            $data->save();

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
            'message' =>'Đã cập nhật bài viết ['.$data->title.'] !',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = Post::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại !',
                ], 404);

            }
            $data->deleted_by = $user->id;
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
            'message' => 'Đã xóa bài viết ['.$data->title.'] !'
        ]);
    }

    public function loadByViews(){
        try{
            $data = Post::orderBy('views','desc')->paginate(9);
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
        return response()->json(new PostCollection($data));
    }

    public function loadAllPostClient(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit;
        try{
            $data = Post::where('is_active', $input['is_active'] ?? 1)->where(function ($query) use ($input) {
                if(!empty($input['title'])){
                    $query->where('title', 'like', '%'.$input['title'].'%');
                }
                if(!empty($input['slug'])){
                    $query->where('slug', $input['slug']);
                }
                if(!empty($input['user_id'])){
                    $query->where('user_id', $input['user_id']);
                }
                if(!empty($input['subcategory_id'])){
                    $query->where('subcategory_id', $input['subcategory_id']);
                }
            })->orderBy('created_at', 'desc')->paginate($input['limit'] ?? 10);
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
        return response()->json(new PostCollection($data));
    }
    public function loadDetailPostClient($id)
    {
        try{
            DB::beginTransaction();
            $data = Post::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại !'
                ], 404);
            }
            $data->views = $data->views + 1;
            $data->save();
            DB::commit();
        }
        catch(HttpException $e){
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
            'data' => new PostResource($data),
        ]);
    }
    //Thống kê số lượng bài viết
    public function statisticalTotalPost()
    {
        try{
            DB::beginTransaction();
            $data = Post::count();
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại !'
                ], 404);
            }
            DB::commit();
        }
        catch(HttpException $e){
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
            'data' =>$data,
        ]);
    }
    public function getFirtsNewPost(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit;
        try{
            $data = Post::where('is_active', $input['is_active'] ?? 1)->orderBy('created_at', 'desc')->limit(1)->get();
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
        return response()->json($data);
    }
    public function getTwoPostAfterNew(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit;
        try{
            $data = Post::where('is_active', $input['is_active'] ?? 1)->orderBy('created_at', 'desc')->offset(2)->limit(2)->get();
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
        return response()->json($data);
    }
    }

