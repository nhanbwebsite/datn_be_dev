<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Validators\User\ClientUserUpdateValidator;
use App\Http\Validators\User\UserCreateValidator;
use App\Http\Validators\User\UserUpdateValidator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
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
            $data = User::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input) {
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['email'])){
                    $query->where('email', 'like', '%'.$input['email'].'%');
                }
                if(!empty($input['phone'])){
                    $query->where('phone', $input['phone']);
                }
                if(!empty($input['address'])){
                    $query->where('address', 'like', '%'.$input['address'].'%');
                }
                if(!empty($input['is_active'])){
                    $query->where('is_active', $input['is_active']);
                }
                if(!empty($input['province_id'])){
                    $query->where('province_id', $input['province_id']);
                }
                if(!empty($input['district_id'])){
                    $query->where('district_id', $input['district_id']);
                }
                if(!empty($input['ward_id'])){
                    $query->where('ward_id', $input['ward_id']);
                }
                if(!empty($input['role_id'])){
                    $query->where('role_id', $input['role_id']);
                }
                if(!empty($input['store_id'])){
                    $query->where('store_id', $input['store_id']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new UserCollection($data);
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
    public function store(Request $request, UserCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $userCreate = User::create([
                'name' => $request->name,
                'address' => $request->address,
                'role_id' => $request->role_id ?? ROLE_USER,
                'ward_id' => $request->ward_id,
                'district_id' => $request->district_id,
                'province_id' => $request->province_id,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active ?? 1,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
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
            'message' => '['.$userCreate->name.'] ???? ???????c t???o th??nh c??ng !',
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
            $data = User::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kh??ng t??m th???y ng?????i d??ng !',
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
            'data' => new UserResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, UserUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $user = User::find($id);
            if(empty($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ng?????i d??ng kh??ng t???n t???i !',
                ], 404);
            }
            $user->name = $request->name ?? $user->name;
            $user->address = $request->address ?? $user->address;
            $user->email = $request->email ?? $user->email;
            $user->role_id = $request->role_id ?? $user->role_id;
            $user->store_id = $request->store_id ?? $user->store_id;
            $user->ward_id = $request->ward_id ?? $user->ward_id;
            $user->district_id = $request->district_id ?? $user->district_id;
            $user->province_id = $request->province_id ?? $user->province_id;
            $user->password = Hash::make($request->password ?? $user->password);
            $user->is_active = $request->is_active ?? $user->is_active;
            $user->updated_by = $request->user()->id;
            $user->save();

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
            'message' => 'Ng?????i d??ng ['.$user->name.'] ???? ???????c c???p nh???t !',
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
        $user = $request->user();
        try{
            DB::beginTransaction();
            if(!is_array($id)){

                if($id == $user->id){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'B???n kh??ng th??? x??a t??i kho???n c???a m??nh !',
                    ], 400);
                }

                $data = User::find($id);
                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Kh??ng t??m th???y ng?????i d??ng !',
                    ], 404);
                }
                if($data->role->level > $user->role->level){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'B???n kh??ng th??? x??a t??i kho???n c?? ph??n quy???n cao h??n !',
                    ], 400);
                }
                $data->deleted_by = $user->id;
                $data->save();

                $data->delete();
            }

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


    public function clientGetUser(Request $request){
        $user = $request->user();
        try{
            $data = User::find($user->id);
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
            'data' => new UserResource($data),
        ]);
    }

    public function clientUpdateUser(Request $request, ClientUserUpdateValidator $validator){
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();

        try{
            DB::beginTransaction();

            $data = User::find($user->id);
            $data->name = $input['name'] ?? $data->name;
            $data->address = $input['address'] ?? $data->address;
            $data->email = $input['email'] ?? $data->email;
            $data->ward_id = $input['ward_id'] ?? $data->ward_id;
            $data->district_id = $input['district_id'] ?? $data->district_id;
            $data->province_id = $input['province_id'] ?? $data->province_id;
            $data->updated_by = $user->id;
            $data->save();

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
            'message' => '???? c???p nh???t th??ng tin !',
        ]);
    }
}
