<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slideshow;
use App\Models\Slideshow_detail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Resources\SlideshowResource;
use App\Http\Resources\SlideshowCollection;
use App\Http\Resources\SlideshowCollectionClient;

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
            'data' => new SlideshowCollection($data)
        ], 200);
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
            'images' => 'required',
            'links' => 'required',
        ];

        $messages = [
            'title.required' => ':attribute không được để trống',
            'images.required' => ':attribute không được để trống',
            'links.required' => ':attribute không được để trống'
        ];

        $attributes = [
            'title' => 'Tiêu đề slideshow',
            'images' => 'hình slideshow',
            'links' => 'Đường dẫn cho slideshow'
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['title', 'images', 'links']), $rules, $messages, $attributes);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            if(!empty($request->category_id)){
                $create = Slideshow::create([
                    'title' => $request->title,
                    'category_id'   => $request->category_id,
                    'slug' => Str::slug($request->title),
                    'created_by' => auth('sanctum')->user()->id
                ]);
            } else {
                $create = Slideshow::create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'created_by' => auth('sanctum')->user()->id
                ]);
            }


            if (!empty($request->images) && !empty($request->links)) {
                foreach ($request->images as $key => $value) {
                    Slideshow_detail::create([
                        'slideshow_id' => $create->id,
                        'image' => $value,
                        'url' => $request->links[$key],
                        'created_by' => auth('sanctum')->user()->id
                    ]);
                }
            }

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ], $e->getStatusCode()); //
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
    public function show($id, Request $request)
    {
        $input = $request->all();
        $id = DB::table('slideshow')->where(function($query) use ($input){
            if(!empty($input['category_id'])){
                $query->where('slideshow.category_id',$input['category_id']);
            } else{
                $query->where('slideshow.category_id',null);
            }
        })->first();
        // $update = Slideshow::select('')
        // dd($id->id);
        $data = Slideshow::find($id->id);
        if (empty($data)) {
            return response()->json([
                'message' =>  'Không tìm thấy slideshow !'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => new SlideshowResource($data)
        ], 200);
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
            'images' => 'required'
        ];

        $messages = [
            'title.required' => ':attribute không được để trống',
            'images.required' => ':attribute không được để trống'
        ];

        $attributes = [
            'title' => 'Tiêu đề slideshow',
            'images' => 'hình'
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['title', 'images']), $rules, $messages, $attributes);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

            $data = Slideshow::find($id);
            if (empty($data)) {
                return response()->json([
                    'message' => 'Slideshow không tồn tại'
                ], 400);
            }
            $data->title = $request->title;
            $data->title = Str::slug($request->title);
            $data->is_active = $request->is_active;
            $data->updated_by = auth('sanctum')->user()->id;
            $data->save;
            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ], $e->getStatusCode()); //
        }
        return response()->json([
            'status' => 'Cập nhật slideshow thành công',
        ], 200);
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
            if (empty($data)) {
                return response()->json([
                    'message' => 'Slideshow không tồn tại'
                ], 400);
            }
            $data->deleted_by = auth('sanctum')->user()->id;
            $data->delete();

            DB::commit();
        } catch (HttpException $e) {
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
        ], 200);
    }

    public function getclientslideshowDetails()
    {
        $data = Slideshow::all();
        return response()->json([
            'status' => 'success',
            'data' => new SlideshowCollectionClient($data)
        ], 200);
    }
}
