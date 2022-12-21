<?php

namespace App\Http\Controllers;
use  App\Http\Resources\BrandCollection;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BrandResource;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $input = $request->all();
            $data = Brands::where('is_post',0)
            ->where('is_active',1)
            ->where('deleted_at',null)
            ->where(function ($query) use ($input) {
                if(!empty($input['name'])){
                    $query->where('brand_name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['slug'])){
                    $query->where('slug', 'like', '%'.$input['slug'].'%');
                }
            })
            ->paginate(9);
            return response()->json(new BrandCollection($data));

        } catch(Exception $e){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
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
        $rules = [
            'brand_name' => 'required|min:2|max:255',
        ];

        $messages = [
            'brand_name.required' => ':attribute không được để trống',
            'brand_name.min' => ':attribute tối thiểu 2 kí tự ',
            'brand_name.max' => ':attribute tối đa 255 kí tự'
        ];

        $attributes = [
            'brand_name' => 'Tên nhà cung cấp',
        ];

        try {

            $validator = Validator::make($request->only('brand_name'), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

            if(!empty($request->is_post)){
                $create =   Brands::create([
                    'brand_name' => $request->brand_name,
                    'slug' => Str::slug($request->brand_name),
                    'is_post' => $request->is_post,
                    'created_by' => auth('sanctum')->user()->id,
                    'updated_by' => auth('sanctum')->user()->id,
                ]);

                if($create) {
                    return response()->json([
                        'status' => 'Created successfully',
                        'data =>' => $create
                    ]);
                }
            } else{
                $create =   Brands::create([
                    'brand_name' => $request->brand_name,
                    'slug' => Str::slug($request->brand_name),
                    'created_by' => auth('sanctum')->user()->id,
                    'updated_by' => auth('sanctum')->user()->id,
                ]);

                if($create) {
                    return response()->json([
                        'status' => 'Created successfully',
                        'data =>' => $create
                    ]);
                }
            }



        } catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]); //
        }

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
            $data = Brands::find($id);
            if(empty($data)) {
                return response()->json([
                    'status' => 'Lỗi',
                    'message' => 'Không tìm thấy thương hiệu !'
                ]); //
            }

            return response()->json(['data' => new BrandResource($data)]);


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
            'brand_name' => 'required|min:2|max:255',
        ];

        $messages = [
            'brand_name.required' => ':attribute không được để trống',
            'brand_name.min' => ':attribute tối thiểu 2 kí tự ',
            'brand_name.max' => ':attribute tối đa 255 kí tự'
        ];

        $attributes = [
            'brand_name' => 'Tên nhà cung cấp',
        ];

        try {

            $validator = Validator::make($request->only('brand_name'), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
          $data = Brands::find($id);

          if(!empty($request->is_post)){

            $data->update([
                'brand_name' => $request->brand_name,
                'slug' => Str::slug($request->brand_name),
                'is_post' => $request->is_post,
                'is_active' => $request->is_active,
                'updated_by' => auth('sanctum')->user()->id,
            ]);

            if($data) {
                return response()->json([
                    'status' => 'Created successfully',
                    'data =>' => $data
                ]);
            }
          } else {
            $data->update([
                'brand_name' => $request->brand_name,
                'slug' => Str::slug($request->brand_name),
                'is_active' => $request->is_active,
                'updated_by' => auth('sanctum')->user()->id,
            ]);

            if($data) {
                return response()->json([
                    'status' => 'Created successfully',
                    'data =>' => $data
                ]);
            }

          }


        } catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]); //
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
            // DB::beginTransaction();
            $data = Brands::find($id);

            if(!empty($data)){
            $data->deleted_by = auth('sanctum')->user()->id;
            $data->save();
            $delete = $data->delete();

                if($delete) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Đã xóa nhà cung cấp'. ' ' . $data['brand_name']
                    ],200);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Lỗi, vui lòng thử lại !'
                ],400);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy nhà cung cấp !'
            ],400);

            // DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function brand_post() {
        try{
            $data = Brands::where('is_post',1)->get();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch(Exception $e){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
        }
    }
}
