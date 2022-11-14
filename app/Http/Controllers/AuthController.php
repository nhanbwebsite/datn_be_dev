<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Validators\Auth\LoginValidator;
use App\Http\Validators\Auth\RegisterValidator;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    /**
     * Login function
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Http\Validators\Auth\LoginValidator $validator
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, LoginValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $remmemberMe = false;
            if(!empty($input['remember'])){
                $remmemberMe = true;
            }
            $data = $request->only(['phone', 'password']);
            $user = Auth::attempt($data, $remmemberMe);
            if(!$user){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Số điện thoại hoặc mật khẩu không đúng !',
                ], 401);
            }

            $userData = User::where('phone', $input['phone'])->first();
            if($userData->is_active == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng đã bị khóa hoặc chưa kích hoạt !',
                ], 401);
            }
            if(empty($userData->session) || $userData->session->expired < date('Y-m-d H:i:s', time())){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phiên đăng nhập đã hết hạn !',
                ], 401);
            }

            $data = RolePermission::where([
                ['role_id', $userData->role_id],
                ['is_active', 1],
            ])->get();
            $permission_code = [];
            foreach($data as $item){
                $permission_code[] = strtolower($item->permission->code);
            }
            $token = $userData->createToken('authToken', $permission_code ?? null)->plainTextToken;

            $oldSession = UserSession::where('user_id', $userData->id);
            $oldSession->update([
                'deleted_by' => $userData->id,
            ]);
            $oldSession->delete();

            $userSessionNew = UserSession::create([
                'user_id' => $userData->id,
                'token' => $token,
                'expired' => date('Y-m-d H:i:s', time()+7*24*60*60),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_by' => $userData->id,
                'updated_by' => $userData->id,
            ]);
            DB::commit();
        }
        catch (HttpException $e){
            DB::rollback();
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
            'message' => 'Đăng nhập bằng ['.$userData->name.'] thành công !',
            'data' => new UserResource($userData),
            'token' => [
                'Bearer' => $token,
                'expired_at' => $userSessionNew->expired,
            ]
        ]);
    }

    /**
     * Register a new user
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Http\Validators\Auth\RegisterValidator $validator
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, RegisterValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $userCreate = User::create([
                'name' => $input['name'],
                'address' => $input['address'],
                'ward_id' => $input['ward_id'],
                'district_id' => $input['district_id'],
                'province_id' => $input['province_id'],
                'phone' => $input['phone'],
                'password' => Hash::make($input['password']),
            ]);
            DB::commit();
        }
        catch(HttpException $e){
            DB::rollback();
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
            'message' => '['.$userCreate->name.'] đã được tạo thành công !',
        ]);
    }

    public function me(Request $request)
    {
        try{
            $user = $request->user();
            if(empty($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chưa đăng nhập !',
                ], 401);
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
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Logout current user
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        try{
            DB::beginTransaction();
            $user = $request->user();
            if(empty($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chưa đăng nhập !',
                ], 401);
            }
            $user->currentAccessToken()->delete();
            if(!empty($user->session)){
                $sessionDel =  UserSession::where('user_id', $user->id);
                $sessionDel->update([
                    'updated_by' => $user->id,
                    'deleted_by' => $user->id,
                ]);
                $sessionDel->delete();
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
        ]);
    }

    public function refresh(Request $request){
        $user = $request->user();
        if(empty($user)){
            return response()->json([
                'status' => 'error',
                'message' => 'Chưa đăng nhập !',
            ], 401);
        }
        try{
            DB::beginTransaction();
            $userData = User::find($user->id);
            $user->currentAccessToken()->delete();

            if(empty($userData->session) || $userData->session->expired < date('Y-m-d H:i:s', time())){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Phiên đăng nhập đã hết hạn !',
                ], 401);
            }

            $data = RolePermission::where([
                ['role_id', $userData->role_id],
                ['is_active', 1],
            ])->get();
            $permission_code = [];
            foreach($data as $item){
                $permission_code[] = strtolower($item->permission->code);
            }

            $token = $userData->createToken('authToken', $permission_code ?? null)->plainTextToken;

            $oldSession = UserSession::where('user_id', $userData->id);
            $oldSession->update([
                'deleted_by' => $userData->id,
            ]);
            $oldSession->delete();

            $userSessionNew = UserSession::create([
                'user_id' => $userData->id,
                'token' => $token,
                'expired' => date('Y-m-d H:i:s', time()+7*24*60*60),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_by' => $userData->id,
                'updated_by' => $userData->id,
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
            'data' => new UserResource($userData),
            'token' => [
                'Bearer' => $token,
                'expired_at' => $userSessionNew->expired,
            ]
        ]);
    }
}
