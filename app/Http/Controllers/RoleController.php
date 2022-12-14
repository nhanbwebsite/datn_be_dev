<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleByLevelCollection;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Http\Validators\Role\RoleCreateValidator;
use App\Http\Validators\Role\RoleUpdateValidator;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleController extends Controller
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
            $data = Role::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input) {
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['level'])){
                    $query->where('level', $input['level']);
                }
                if(!empty($input['is_active'])){
                    $query->where('is_active', $input['is_active']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new RoleCollection($data);
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
        return response()->json($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RoleCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
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
            'message' => 'Vai tr?? ['.$roleCreate->name.'] ???? ???????c t???o th??nh c??ng !',
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
                    'message' => 'Kh??ng t??m th???y vai tr?? !',
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
    public function update(Request $request, $id, RoleUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $user = $request->user();
            $roleUpdate = Role::find($id);
            if(empty($roleUpdate)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vai tr?? kh??ng t???n t???i !',
                ], 404);
            }
            $roleUpdate->name = $request->name ?? $roleUpdate->name;
            $roleUpdate->level = $request->level ?? $roleUpdate->level;
            $roleUpdate->is_active = $request->is_active ?? $roleUpdate->is_active;
            $roleUpdate->updated_by = $user->id;
            $roleUpdate->save();
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
            'message' => 'Vai tr?? ['.$roleUpdate->name.'] ???? ???????c c???p nh???t !',
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
            $data = Role::find($id);
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
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'status' => 'success',
            'message' => '???? x??a ['.$data->name.']',
        ]);
    }

    public function roleByLevel(Request $request){
        $user = $request->user();
        try{

            $data = Role::where('level', '<', $user->role->level)->get();

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
        return response()->json(new RoleByLevelCollection($data));
    }
}
