<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slideshow;
use App\Models\Slideshow_detail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SlideshowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Slideshow::paginate(9);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ],200);
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
            'title' => 'required',
            'image' => 'Hình không được bỏ trống'
        ];

        $messages = [
            'title.required' => ':attribute tên màu không được để trống'
        ];

        $attributes = [
            'title' => 'Tiêu đề slideshow'
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['title']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

          $create = Slideshow::create([
                'name' => $request->title,
                'slug' => Str::slug($request->title),
                'created_by' => auth('sanctum')->user()->id
            ]);

            if(!empty($request->image)){
                foreach($request->image as $key => $value){
                    Slideshow_detail::create([
                        'slideshow_id' => $create->id,
                        'image' => $request->value
                    ]);
                }
            }

            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ],$e->getStatusCode()); //
        }
        return response()->json([
            'status' => 'Created successfully',
            'data =>' => $create
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
        $data = Slideshow::find($id);
        if(empty($data)){
            return response()->json([
                'message' =>  'Không tìm thấy slideshow !'
            ],400);
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
            'title' => 'required',
        ];

        $messages = [
            'title.required' => ':attribute tên màu không được để trống'
        ];

        $attributes = [
            'title' => 'Tiêu đề slideshow'
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['title']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

            $data = Slideshow::find($id);
            if(empty( $data)){
                return response()->json([
                    'message' => 'Slideshow không tồn tại'
                ],400);
            }
            $data->title = $request->title;
            $data->title = Str::slug($request->title);
            $data->is_active = $request->is_active;
            $data->updated_by = auth('sanctum')->user()->id;
            $data->save;
            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ],$e->getStatusCode()); //
        }
        return response()->json([
            'status' => 'Cập nhật slideshow thành công',
        ],200);
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

            $data = Slideshow::find($id);
            if(empty( $data)){
                return response()->json([
                    'message' => 'Slideshow không tồn tại'
                ],400);
            }
            $data->deleted_by = auth('sanctum')->user()->id;
            $data->delete();

            DB::commit();

        } catch(HttpException $e) {
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
            'message' => 'Xóa slideshow thành công'
        ],200);
    }
}
