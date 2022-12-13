<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rep_comment;
class ReplyCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Rep_comment::find($id);
        if(auth('sanctum')->user()->role_id == 4 || auth('sanctum')->user()->role_id == 1 ||auth('sanctum')->user()->id == $data->created_by){
            if($data){
                $data->deleted_by = auth('sanctum')->user();
                $data->delete();
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy bình luận !',
                ], 401);
            }
        } else{
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn không có quyền xóa bình luận này !',
            ], 401);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa bình luận thành công !',
        ], 200);
    }
}
