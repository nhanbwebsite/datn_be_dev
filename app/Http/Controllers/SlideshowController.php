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
            if (!empty($request->category_id)) {
                $create = Slideshow::create([
                    'title' => $request->title,
                    'category_id'   => $request->category_id,
                    'slug' => Str::slug($request->title),
                    'created_by' => $request->user()->id,

                ]);
            } else {
                $create = Slideshow::create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'created_by' => auth('sanctum')->user()->id,

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
        $id = Slideshow::where(function ($query) use ($input) {
            if (!empty($input['category_id'])) {
                $query->where('slideshow.category_id', $input['category_id']);
            } else {
                $query->where('slideshow.category_id', null);
            }
        })->where('is_active', 1)->first();
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
            $data_detail = Slideshow_detail::where('slideshow_id', $data->id)->delete();
            $data->is_active = 0;
            $data->save();
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
        $data = Slideshow::where('is_active', 1)->get();

        return response()->json([
            'status' => 'success',
            'data' => new SlideshowCollectionClient($data)
        ], 200);
    }


    public function showSlideBycate(Request $request)
    {

        // dd($request->all());
        $input = $request->category_id;
        if (!empty($input)) {
            $data = Slideshow::where('category_id', $input)->where('is_active', 1)->first();
            // $update = Slideshow::select('')
            // dd($id->id);

            if (empty($data)) {
                return $data = [];
            }

            return response()->json([
                'status' => 'success',
                'data' => new SlideshowResource($data)
            ], 200);
        }
        // dd($input);

    }

    // update slide by category

    public function showSlideBycateUpdate(Request $request)
    {

        // dd($request->all());
        $input = $request->all();
        if (!empty($input['category_id'])) {
            $data = Slideshow::where('category_id', $input['category_id'])->update([
                'is_active' => 0
            ]);
            //  dd($data);
            $update_active = Slideshow::find($input['slide_id']);
            $update_active->is_active = 1;
            $update_active->save();
            // $update = Slideshow::select('')
            // dd($id->id);

            if (empty($update_active)) {
                return response()->json([
                    'message' =>  'Không tìm thấy slideshow !'
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Đã cập nhật Slide thành công'
            ], 200);
        }
        // dd($input);

        if (!empty($input['delete_detail_id'])) {
            $data_delete = Slideshow_detail::find($input['delete_detail_id']);
            $data_delete->delete();
        }
    }

    public function listSlideshowByCate()
    {
        $data = Slideshow::whereNotNull('category_id')->paginate(9);
        return response()->json(new SlideshowCollection($data), 200);
    }


    public function listSlideshowMain()
    {
        $data = Slideshow::whereNull('category_id')->paginate(9);

        return response()->json(new SlideshowCollectionClient($data), 200);
    }

    public function updateSlideMain(Request $request)
    {
        if($request->slide_id_active){
            $data_active = Slideshow::find($request->slide_id_active);
            $data_un_active = Slideshow::whereNull('category_id')->update([
                'is_active' => 0
            ]);

            $data_active->is_active = 1;
            $data_active->save();
            return response()->json(
                [
                    'message' => 'Cập nhật trạng thái hiển thị slideshow thành công'
                ]
            );
        }

        $data_update = Slideshow_detail::find($request->slide_id);
        if($data_update){
            $data_update->slideshow_id = $request->slideshow_id;
            $data_update->image = $request->image;
            $data_update->url = $request->url;
            $data_update->is_active = $request->is_active;
            $data_update->save();
            return response()->json(
                [
                    'message' => 'Cập nhật thành công !'
                ] , 200
            );
        }


    }

    public function deleteSlideDetails($id)
    {

        $data_delete = Slideshow_detail::find($id);
        if ($data_delete) {
            $data_delete->delete();
            return response()->json(
                [
                    'message' => 'Xóa thành công !'
                ],
                200
            );
        }

        return response()->json(
            [
                'message' => 'Không tìm thấy !'
            ],
            400
        );
    }
}
