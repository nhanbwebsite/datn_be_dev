<?php

namespace App\Http\Controllers;

use App\Http\Resources\FooterCategoryResource;
use App\Http\Resources\FooterContentCollection;
use App\Http\Resources\FooterContentResource;
use App\Http\Resources\LoadFooterContentClientCollection;
use App\Http\Resources\LoadFooterContentResource;
use App\Http\Validators\FooterContent\FooterContentCreateValidator;
use App\Http\Validators\FooterContent\FooterContentUpdateValidator;
use App\Models\FooterCategory;
use App\Models\FooterContent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class FooterContentController extends Controller
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
            $data = FooterContent::where('is_active', $input['is_active'] ?? 1)->where(function ($query) use ($input) {
                if(!empty($input['title'])){
                    $query->where('title', 'like', '%'.$input['title'].'%');
                }
                if(!empty($input['user_id'])){
                    $query->where('user_id', $input['user_id']);
                }
                if(!empty($input['is_active'])){
                    $query->where('is_active', $input['is_active']);
                }
            })->orderBy('created_at', 'asc')->paginate($input['limit'] ?? 10);
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
        return response()->json(new FooterContentCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FooterContentCreateValidator $validator)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['title']) ?? null;
        $validator->validate($input);
        $user = $request->user();
        try {
            DB::beginTransaction();
            $data = FooterContent::create([
                'category_id'=>$input['category_id'],
                'user_id'=> $user->id,
                'title'=>mb_strtoupper(mb_substr($input['title'], 0, 1)).mb_substr($input['title'], 1),
                'content'=> $input['content'],
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
     * @param  \App\Models\FooterContent  $FooterContent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            DB::beginTransaction();
            $data = FooterContent::find($id);
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
            'data' => new FooterContentResource($data),
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FooterContent  $FooterContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FooterContentUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = FooterContent::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại !'
                ], 404);
            }

            $data->category_id = $input['category_id'] ?? $data->category_id;
            $data->title = mb_strtoupper(mb_substr($input['title'], 0, 1)).mb_substr($input['title'], 1) ?? $data->title;
            $data->content = $input['content'] ?? $data->content;
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
     * @param  \App\Models\FooterContent  $FooterContent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = FooterContent::find($id);
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

    public function loadAll()
    {
        try{
            $data = FooterContent::orderBy('created_at','desc')->get();
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
        return response()->json(new LoadFooterContentClientCollection($data));
    }

    public function loadClient($id)
    {
        try{
            DB::beginTransaction();
            $data = FooterContent::find($id);
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
            'data' => new FooterContentResource($data),
        ]);
    }

}
