<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupPermissionCollection;
use App\Http\Resources\GroupPermissionResource;
use App\Http\Validators\GroupPermission\GroupPermissionCreateValidator;
use App\Http\Validators\GroupPermission\GroupPermissionUpdateValidator;
use App\Models\GroupPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $data = GroupPermission::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input){
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
    public function store(Request $request, GroupPermissionCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
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
            'message' => 'Nh??m quy???n ['.$groupPermissionCreate->name.'] ???? ???????c t???o th??nh c??ng !',
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
                    'message' => 'Kh??ng t??m th???y vai tr?? !',
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
    public function update(Request $request, $id, GroupPermissionUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $user = $request->user();
            $groupPermissionUpdate = GroupPermission::find($id);
            if(empty($groupPermissionUpdate)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nh??m quy???n kh??ng t???n t???i !',
                ], 404);
            }
            $groupPermissionUpdate->name = $request->name ?? $groupPermissionUpdate->name;
            $groupPermissionUpdate->table_name = $request->table_name ?? $groupPermissionUpdate->table_name;
            $groupPermissionUpdate->name = $user->id;
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
            'message' => 'Nh??m quy???n ['.$groupPermissionUpdate->name.'] ???? ???????c c???p nh???t !',
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
            $data = GroupPermission::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vai tr?? kh??ng t???n t???i !',
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
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
        return response()->json([
            'status' => 'success',
            'message' => '???? x??a ['.$data->name.']',
        ]);
    }
}
