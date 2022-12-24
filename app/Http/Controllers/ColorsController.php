<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ColorCollection;
class ColorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $dataReturn = '';
        $data = Color::where('is_active',$input['is_active'] ?? 1)->where('deleted_at',null)->where(function ($query) use ($input){
            if(!empty($input['name'])){
                $query->where('name', 'like', '%'.$input['name'].'%');
            }
            if(!empty($input['slug'])){
                $query->where('slug', 'like', '%'.$input['slug'].'%');
            }
        });

        if(isset($input['paginate'])) {
          $dataReturn =  $data->paginate($input['paginate']);
        } else{
            $dataReturn = $data->paginate(9);
        }

        return response()->json([
            'status' => 'success',
            'data'  => new ColorCollection($dataReturn),
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

        $user = auth('sanctum')->user();
        $rules = [
            'name' => 'required',
            'color_code' => 'required',
        ];

        $messages = [
            'name.required' => ':attribute tên màu không được để trống',
            'color_code.required' => ':attribute không được để trống'
        ];

        $attributes = [
            'name' => 'Tên màu',
            'color_code' => 'Mã màu'
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->only(['name','color_code']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

          $create =   Color::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                "color_code" => $request->color_code,
                'created_by' => $user->id
            ]);

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
        try{
            $data = Color::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Màu không tồn tại !',
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
            'data' => $data,
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
        $user = auth('sanctum')->user();
        $rules = [
            'name' => 'required',
            'color_code' => 'required',
        ];

        $messages = [
            'name.required' => ':attribute tên màu không được để trống',
            'color_code.required' => ':attribute không được để trống'
        ];

        $attributes = [
            'name' => 'Tên màu',
            'color_code' => 'Mã màu'
        ];

        $validator = Validator::make($request->only(['name','color_code']), $rules, $messages, $attributes);
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }
        try {
            DB::beginTransaction();
            $data = Color::find($id);
            $data->name = $request->name;
            $data->slug = Str::slug($request->name);
            $data->color_code = $request->color_code;
            $data->is_active = $request->is_active;
            $data->updated_by = $user->id;
            $data->save();
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
            'status' => 'success',
            'message' => 'Cập nhật màu thành công !',
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
        $user = auth('sanctum')->user();
        try {
            DB::beginTransaction();
            $data = Color::find($id);

            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy màu !!'
                ], 404);
            }

            $data->delete_by = $user->id;
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
            'message' => 'Đã xóa ['.$data->name.'] !',
        ]);
    }

    public function allColor(Request $request)
    {
        $input = $request->all();
        $dataReturn = '';
        $data = Color::where('is_active',$input['is_active'] ?? 1)->where('deleted_at',null)->where(function ($query) use ($input){
            if(!empty($input['name'])){
                $query->where('name', 'like', '%'.$input['name'].'%');
            }
            if(!empty($input['slug'])){
                $query->where('slug', 'like', '%'.$input['slug'].'%');
            }
        })->get();


        return response()->json([
            'status' => 'success',
            'data'  => $data
        ],200);
    }

}

