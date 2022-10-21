<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input['limit'] = $request->limit;
        try{
            $data = Comment::where('is_active', 1)->where(function($query) use($input){
                if(!empty($input['parent_id'])){
                    $query->where('parent_id', $input['parent_id']);
                }
                if(!empty($input['user_id'])){
                    $query->where('user_id', $input['user_id']);
                }
                if(!empty($input['post_id'])){
                    $query->where('post_id', $input['post_id']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new CommentCollection($data);
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
        return response()->json($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try{
            DB::beginTransaction();
            $validator = $this->upsertValidate($input);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            Comment::create([
                'user_id' => auth('sanctum')->user()->id,
                'parent_id' => $request->parent_id ?? null,
                'post_id' => $request->post_id,
                'content' => $request->content,
                'created_by' => auth('sanctum')->user()->id,
                'updated_by' => auth('sanctum')->user()->id,
            ]);
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
            'message' => 'Đã lưu bình luận của bạn, quản trị viên sẽ xem xét phê duyệt !',
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
            $data = Comment::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy bình luận !',
                ], 404);
            }
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
        return response()->json([
            'status' => 'success',
            'data' => new CommentResource($data),
        ]);
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
        $input = $request->all();
        $input['id'] = $id;
        try {
            DB::beginTransaction();
            $validator = $this->upsertValidate($input);
            $comment = Comment::find($id);
            if(empty($comment)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại !',
                ], 404);
            }
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $comment->content = $request->content ?? $comment->content;
            $comment->is_active = $request->is_active ?? $comment->is_active;
            $comment->updated_by = auth('sanctum')->user()->id;
            $comment->save();

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
            'message' => 'Bình luận đã được cập nhật !',
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
        try{
            DB::beginTransaction();
            if(!is_array($id)){
                $data = Comment::find($id);
                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy bình luận !',
                    ], 404);
                }
                $data->is_delete = 1;
                $data->deleted_by = auth('sanctum')->user()->id;
                $data->save();

                $data->delete();
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
            'message' => 'Đã xóa bình luận !',
        ]);
    }

    public function upsertValidate($input){
        if(!empty($input['id'])){
            $rules = [
                'user_id' => 'required|numeric|exists:users,id',
                'post_id' => 'required|numeric|exists:posts,id',
                'content' => 'required',
                'is_active' => 'required|numeric',
            ];

            $messages = [
                'user_id.required' => ':attribute không được để trống !',
                'user_id.numeric' => ':attribute chưa đúng !',
                'post_id.required' => ':attribute không được để trống !',
                'post_id.numeric' => ':attribute chưa đúng !',
                'content.required' => ':attribute không được để trống !',
                'is_active.required' => ':attribute không được để trống !',
                'is_active.numeric' => ':attribute chưa đúng !',
            ];

            $attributes = [
                'user_id' => 'Mã người dùng',
                'post_id' => 'Mã bài viết',
                'content' => 'Nội dung',
                'is_active' => 'Trạng thái',
            ];
        }
        else{
            $rules = [
                'user_id' => 'required|numeric|exists:users,id',
                'post_id' => 'required|numeric|exists:posts,id',
                'content' => 'required',
            ];

            $messages = [
                'user_id.required' => ':attribute không được để trống !',
                'user_id.numeric' => ':attribute chưa đúng !',
                'post_id.required' => ':attribute không được để trống !',
                'post_id.numeric' => ':attribute chưa đúng !',
                'content.required' => ':attribute không được để trống !',
            ];

            $attributes = [
                'user_id' => 'Mã người dùng',
                'post_id' => 'Mã bài viết',
                'content' => 'Nội dung',
            ];
        }
        $v = Validator::make($input, $rules, $messages, $attributes);
        return $v;
    }
}
