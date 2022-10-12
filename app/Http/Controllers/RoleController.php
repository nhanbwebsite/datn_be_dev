<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = Role::orderBy('created_at', 'desc')->paginate();
            $resource = new RoleCollection($data);
            $result = $resource->toArray($data);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'data' => $result['data'],
            'paginator' => $result['paginator'],
        ]);
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
            'code' => 'required|string|max:50|unique:roles,code',
            'name' => 'required|string|max:50',
            'level' => 'required|numeric',
        ];

        $messages = [
            'code.required' => ':attribute không được để trống !',
            'code.string' => ':attribute phải là chuỗi !',
            'code.max' => ':attribute tối đa 50 ký tự !',
            'code.unique' => ':attribute đã tồn tại !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'level.required' => ':attribute không được để trống !',
            'level.numeric' => ':attribute phải là số !',
        ];

        $attributes = [
            'code' => 'Mã vai trò',
            'name' => 'Tên vai trò',
            'level' => 'Cấp',
        ];

        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $roleCreate = Role::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'level' => $request->level,
                'is_active' => $request->is_active ?? 1,
                'created_by' => auth('sanctum')->user()->id,
                'updated_by' => auth('sanctum')->user()->id,
            ]);
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Vai trò ['.$roleCreate->name.'] đã được tạo thành công !',
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
            $data = Role::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy vai trò !',
                ], 404);
            }
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'data' => new RoleResource($data),
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
        $rules = [
            'name' => 'required|string|max:50',
            'level' => 'required|numeric',
        ];

        $messages = [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'level.required' => ':attribute không được để trống !',
            'level.numeric' => ':attribute phải là số !',
        ];

        $attributes = [
            'name' => 'Tên vai trò',
            'level' => 'Cấp',
        ];

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), $rules, $messages, $attributes);
            if($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $roleUpdate = Role::find($id);
            if(empty($roleUpdate)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vai trò không tồn tại !',
                ], 404);
            }
            $roleUpdate->name = $request->name ?? $roleUpdate->name;
            $roleUpdate->level = $request->level ?? $roleUpdate->level;
            $roleUpdate->updated_by = auth('sanctum')->user()->id;
            $roleUpdate->save();
            DB::commit();
        }
        catch (Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        };
        return response()->json([
            'status' => 'success',
            'message' => 'Vai trò ['.$roleUpdate->name.'] đã được cập nhật !',
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
            $data = Role::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vai trò không tồn tại !',
                ], 404);
            }
            $data->update([
                'is_delete' => 1,
                'deleted_by' => auth('sanctum')->user()->id
            ]);
            $data->delete();
            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa ['.$data->name.']',
        ]);
    }
}
