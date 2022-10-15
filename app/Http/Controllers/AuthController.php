<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    /**
     * Login function
     *
     * @param Request $request
     * @return response
     */
    public function login(Request $request)
    {
        $rules = [
            'phone' => 'required|string|regex:/^0[2-9]{1}[0-9]{8}$/',
            'password' => 'required|string',
        ];

        $messages = [
            'phone.required' => ':attribute không được để trống !',
            'phone.string' => ':attribute phải là chuỗi !',
            'phone.regex' => ':attribute chưa đúng định dạng ! VD: 0946636842',
            'password.required' => ':attribute không được để trống !',
            'password.string' => ':attribute phải là chuỗi !',
        ];

        $attributes = [
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
        ];

        try{
            DB::beginTransaction();
            $validator = Validator::make($request->only(['phone', 'password']), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
            $remmemberMe = false;
            if(!empty($request->remember)){
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
            $userData = User::where('phone', $request->phone)->first();
            if($userData->is_active == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng đã bị khóa hoặc chưa kích hoạt !',
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

            if(!empty($userData->session)){
                $oldSession = UserSession::where('user_id', $userData->id);
                $oldSession->update([
                    'is_delete' => 1,
                    'updated_by' => $userData->id,
                    'deleted_by' => $userData->id,
                ]);
                $oldSession->delete();
            }

            UserSession::create([
                'user_id' => $userData->id,
                'token' => $token,
                'expired' => date('Y-m-d H:i:s', time()+7*24*60*60),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_by' => $userData->id,
            ]);
            DB::commit();
        }
        catch (HttpException $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập bằng ['.$userData->phone.'] thành công !',
            'data' => new UserResource($userData),
            'token' => 'Bearer '.$token,
        ]);
    }

    /**
     * Register function
     *
     * @param Request $request
     * @return response
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'ward_id' => 'required',
            'district_id' => 'required',
            'province_id' => 'required',
            'phone' => 'required|string|min:10|unique:users,phone|regex:/^0[2-9]{1}[0-9]{8}$/',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|string|min:8|same:password',
        ];

        $messages = [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'address.string' => ':attribute phải là chuỗi !',
            'address.max' => ':attribute tối đa 255 ký tự!',
            'ward_id.required' => ':attribute không được để trống !',
            'district_id.required' => ':attribute không được để trống !',
            'province_id.required' => ':attribute không được để trống !',
            'phone.required' => ':attribute không được để trống !',
            'phone.string' => ':attribute phải là chuỗi !',
            'phone.min' => ':attribute phải đủ 10 ký tự !',
            'phone.unique' => ':attribute đã được đăng ký !',
            'phone.regex' => ':attribute chưa đúng định dạng VD: 0946636842 !',
            'password.required' => ':attribute không được để trống !',
            'password.string' => ':attribute phải là chuỗi !',
            'password.min' => ':attribute tối thiểu 8 ký tự !',
            'password.confirmed' => ':attribute không đúng !',
            'password_confirm.same' => ':attribute không đúng !',
            'password_confirm.required' => ':attribute không được để trống !',
            'password_confirm.string' => ':attribute phải là chuỗi !',
            'password_confirm.min' => ':attribute tối thiểu 8 ký tự !',
        ];

        $attributes = [
            'name' => 'Họ tên',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
            'password_confirm' => 'Xác nhận mật khẩu',
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

            $userCreate = User::create([
                'name' => $request->name,
                'address' => $request->address,
                'ward_id' => $request->ward_id,
                'district_id' => $request->district_id,
                'province_id' => $request->province_id,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
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
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
        ]);
    }

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
                    'is_delete' => 1,
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
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng xuất thành công !',
        ]);
    }
}
