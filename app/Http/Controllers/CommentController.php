<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Http\Validators\Comment\CommentCreateValidator;
use App\Http\Validators\Comment\CommentUpdateValidator;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Rep_comment;
use Faker\Core\Number;

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
            $data = Comment::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input){
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
    public function store(Request $request, CommentCreateValidator $validator)
    {


        try{
            DB::beginTransaction();
            $is_active = 0;
            $rep = false;
            if(auth('sanctum')->user()->role_id == 1 || auth('sanctum')->user()->role_id == 4) {
                $is_active = 1;
            }
            if(!empty($request->id_comment)){

                $rules = [
                    'rep_comment' => 'required|min:2|max:255',
                ];

                $messages = [

                    'rep_comment.required' => ':attribute tối thiểu 2 kí tự ',

                ];

                $attributes = [
                    'rep_comment' => 'Nội dung bình luận',
                ];
                $validator = Validator::make($request->only('rep_comment'), $rules, $messages, $attributes);
                if($validator->fails()){
                    return response()->json([
                        'status' => 'error',
                        'message' => $validator->errors(),
                    ], 422);
                }
                Rep_comment::create([
                    'id_comment' => $request->id_comment,
                    'rep_comment' => $request->rep_comment,
                    'is_active' => $is_active,
                    'created_by' => auth('sanctum')->user()->id,
                    'updated_by' => auth('sanctum')->user()->id
                ]);

                $rep = true;
            } else{
                $input = $request->all();
                $validator->validate($input);
                Comment::create([
                    "user_id" => auth('sanctum')->user()->id,
                    'product_id' => $request->product_id,
                    'content' => $request->content,
                    'is_active' => $is_active,
                    'created_by' => auth('sanctum')->user()->id,
                    'updated_by' => auth('sanctum')->user()->id,

                ]);

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



        $mess = 'Đã lưu bình luận của bạn, quản trị viên sẽ xem xét phê duyệt !';
        if($is_active == 1){
        $mess = 'Bình luận thành công !';
        }

        if($rep == true){
            $mess = 'Trả lời bình luận thành công !';
            return response()->json([
                'status' => 'success',
                'message' => $mess,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $mess,
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
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, CommentUpdateValidator $validator)
    {
        $input = $request->all();
        $user = $request->user();

        try {
            DB::beginTransaction();

            if(!empty($request->rep_id_comment)){
                $comment = Rep_comment::find($request->rep_id_comment);
                if($comment){
                    $comment->rep_comment = $request->rep_comment;
                    $comment->is_active = $request->is_active;
                    $comment->updated_by = $user->id;
                    $comment->save();
                } else{
                     return response()->json([
                        'status' => 'error',
                        'message' => 'Bình luận không tồn tại !',
                    ], 404);
                }

            } else{
            $validator->validate($input);
            $comment = Comment::find($id);
            if(empty($comment)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bình luận không tồn tại !',
                ], 404);
            }

            $comment->content = $request->content ?? $comment->content;
            $comment->is_active = $request->is_active ?? $comment->is_active;
            $comment->updated_by = $user->id;
            $comment->save();
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
            'message' => 'Bình luận đã được cập nhật !',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try{
            DB::beginTransaction();
            $user = $request->user();

            if(!empty($request->rep_id_comment)){
                $data = Rep_comment::find($request->rep_id_comment);
                if($user->role_id == 4 || $user->role_id == 1 || $user->id == $data->created_by){
                    if($data){
                        $data->is_active = 0;
                        $data->save();
                        $data->delete();
                    } else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Không tìm thấy bình luận !',
                        ], 404);
                    }
                }

            } else{

                $data = Comment::find($id);

                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy bình luận !',
                    ], 404);
                }
                if($user->role_id == 4 || $user->role_id == 1 || $user->id == $data->user_id){
                    $data->deleted_by = $user->id;
                    $data->is_active = 0;
                    $data->save();

                    $data->delete();
                    } else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Bạn không có quyền xóa bình luận này !',
                        ], 401);
                }
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


    public function getAllCommentByProductId(Request $request,$pro_id)
    {
        // $input['limit'] = $request->limit;
        try{
            $data = Comment::where('product_id',$pro_id)->where('deleted_at',null)->paginate(9);
            // $data = Comment::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input){
            //     if(!empty($input['user_id'])){
            //         $query->where('user_id', $input['user_id']);
            //     }
            //     if(!empty($input['post_id'])){
            //         $query->where('post_id', $input['post_id']);
            //     }
            // })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
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

    public static function getReplyCommenproductByIdComment($id){
        $data = DB::table('rep_comments')
                ->select('users.name as user_reply','users.id as user_id','rep_comments.*')
                ->join('comments','rep_comments.id_comment','comments.id')
                ->join('users','comments.user_id','users.id')
                ->where('rep_comments.id_comment',$id)
                ->where('rep_comments.deleted_at',null)
                ->get();

        return response()->json([
            'data' => $data,
        ],200);
    }

    public function getCommentUnactive(){
        $data_comment = DB::table('comments')
        ->select(DB::raw('count(*) as total'))
        ->where('comments.is_active',0)
        ->where('comments.deleted_at',null)
        ->first()->total;
        $data_rep_comment = DB::table('rep_comments')
        ->select(DB::raw('count(*) as total'))
        ->where('rep_comments.deleted_at',null)
        ->where('rep_comments.is_active',0)
        ->first()->total;
        $data = $data_comment + $data_rep_comment;
        return response()->json( ['data' => $data],200);
    }

}
