<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupPermissionCollection;
use App\Http\Resources\GroupPermissionResource;
use App\Models\GroupPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = GroupPermission::orderBy('created_at', 'desc')->paginate();
            $resource = new GroupPermissionCollection($data);
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
            'code' => 'required|string|max:50|unique:group_permissions,code',
            'name' => 'required|string|max:50',
            'table_name' => 'string',
        ];

        $messages = [
            'code.required' => ':attribute không được để trống !',
            'code.string' => ':attribute phải là chuỗi !',
            'code.max' => ':attribute tối đa 50 ký tự !',
            'code.unique' => ':attribute đã tồn tại !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'table_name.string' => ':attribute phải là chuỗi !',
        ];

        $attributes = [
            'code' => 'Mã nhóm quyền',
            'name' => 'Tên nhóm quyền',
            'table_name' => 'Tên bảng',
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
            $groupPermissionCreate = GroupPermission::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'table_name' => $request->table_name ?? null,
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
            'message' => 'Nhóm quyền ['.$groupPermissionCreate->name.'] đã được tạo thành công !',
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
            $data = GroupPermission::find($id);
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
            'data' => new GroupPermissionResource($data),
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
            'table_name' => 'string',
        ];

        $messages = [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'table_name.string' => ':attribute phải là chuỗi !',
        ];

        $attributes = [
            'name' => 'Tên nhóm quyền',
            'table_name' => 'Tên bảng',
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
            $groupPermissionUpdate = GroupPermission::find($id);
            if(empty($groupPermissionUpdate)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nhóm quyền không tồn tại !',
                ], 404);
            }
            $groupPermissionUpdate->name = $request->name ?? $groupPermissionUpdate->name;
            $groupPermissionUpdate->table_name = $request->table_name ?? $groupPermissionUpdate->table_name;
            $groupPermissionUpdate->name = auth('sanctum')->user()->id;
            $groupPermissionUpdate->save();
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
            'message' => 'Nhóm quyền ['.$groupPermissionUpdate->name.'] đã được cập nhật !',
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
            $data = GroupPermission::find($id);
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