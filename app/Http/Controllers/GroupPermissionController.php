<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupPermissionCollection;
use App\Http\Resources\GroupPermissionResource;
use App\Models\GroupPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GroupPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit;
        try{
            $data = GroupPermission::where('is_active', 1)->where(function($query) use($input){
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['is_active'])){
                    $query->where('is_active', $input['is_active']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new GroupPermissionCollection($data);
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
        catch(HttpException $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
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
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
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
        $input = $request->all();
        $input['id'] = $id;
        try{
            DB::beginTransaction();
            $validator = $this->upsertValidate($input);
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
        catch(HttpException $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
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
        catch(HttpException $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa ['.$data->name.']',
        ]);
    }

    public function upsertValidate($input){
        if(!empty($input['id'])){
            $rules = [
                'name' => 'required|string|max:50',
                'table_name' => 'string',
                'is_active' => 'numeric',
            ];

            $messages = [
                'name.required' => ':attribute không được để trống !',
                'name.string' => ':attribute phải là chuỗi !',
                'name.max' => ':attribute tối đa 50 ký tự !',
                'table_name.string' => ':attribute phải là chuỗi !',
                'is_active.numeric' => ':attribute phải là số !',
            ];

            $attributes = [
                'name' => 'Tên nhóm quyền',
                'table_name' => 'Tên bảng',
                'is_active' => 'Kích hoạt',
            ];
        }
        else{
            $rules = [
                'code' => 'required|string|max:50|unique:group_permissions,code',
                'name' => 'required|string|max:50',
                'table_name' => 'string',
                'is_active' => 'numeric',
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
                'is_active.numeric' => ':attribute chưa đúng !',
            ];

            $attributes = [
                'code' => 'Mã nhóm quyền',
                'name' => 'Tên nhóm quyền',
                'table_name' => 'Tên bảng',
                'is_active' => 'Kích hoạt',
            ];
        }
        $v = Validator::make($input, $rules, $messages, $attributes);
        return $v;
    }
}
