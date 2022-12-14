<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolePermissionCollection;
use App\Http\Resources\RolePermissionResource;
use App\Http\Validators\RolePermission\RolePermissionCreateValidator;
use App\Http\Validators\RolePermission\RolePermissionUpdateValidator;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
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
            $data = RolePermission::where('is_active', $input['is_active'] ?? 1)->where(function($query) use($input){
                if(!empty($input['role_id'])){
                    $query->where('role_id', $input['role_id']);
                }
                if(!empty($input['permission_id'])){
                    $query->where('permission_id', $input['permission_id']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new RolePermissionCollection($data);
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
    public function store(Request $request, RolePermissionCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $roleData = Role::find($request->role_id);
            $permissionData = Permission::find($request->permission_id);
            RolePermission::create([
                'role_id' => $request->role_id,
                'permission_id' => $request->permission_id,
                'is_active' => $request->is_active ?? 1,
                'created_by' => auth('sanctum')->user()->id,
                'updated_by' => auth('sanctum')->user()->id,
            ]);
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
            'message' => 'Quy???n ['.$permissionData->name.'] ???? ???????c g??n th??nh c??ng cho ['.$roleData->name.'] !',
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
            $data = RolePermission::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kh??ng t??m th???y quy???n !',
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
            'data' => new RolePermissionResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, RolePermissionUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $rolePermissionUpdate = RolePermission::find($id);
            if(!empty($rolePermissionUpdate)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quy???n kh??ng t???n t???i !',
                ], 404);
            }
            $rolePermissionUpdate->role_id = $request->role_id ?? $rolePermissionUpdate->role_id;
            $rolePermissionUpdate->permission_id = $request->permission_id ?? $rolePermissionUpdate->permission_id;
            $rolePermissionUpdate->is_active = $request->is_active ?? $rolePermissionUpdate->is_active;
            $rolePermissionUpdate->updated_by = $request->user()->id;
            $rolePermissionUpdate->save();
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
            'message' => '???? s???a th??nh c??ng !',
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
            $data = RolePermission::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kh??ng t??m th???y quy???n !',
                ], 404);
            }
            $data->update([
                'deleted_by' => $user->id
            ]);
            $data->delete();
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
            'message' => '???? x??a !',
        ]);
    }
}
