<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Validators\Auth\LoginValidator;
use App\Http\Validators\Auth\RegisterValidator;
use App\Http\Validators\SMS\SMSValidator;
use App\Models\RolePermission;
use App\Models\SmsRequest;
use App\Models\User;
use App\Models\UserSession;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    /**
     * Login with phone and code (SMS)
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
            if(!empty($input['remember']) && $input['remember'] == 1){
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
            // Auth::logoutOtherDevices($input['password']);
            // $userData = User::where('phone', $input['phone'])->first();
            $userData = User::find(Auth::id());
            if(env('SMS_ENABLE') == 1){
                if(!empty($userData->request_code_at) && (date('Y-m-d H:i:s', time()-60) > $userData->request_code_at)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Mã đã hết hiệu lực !'
                    ], 400);
                }
            }
            if($userData->is_active == 0){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng đã bị khóa hoặc chưa kích hoạt !',
                ], 401);
            }
            $oldSession = UserSession::where('user_id', $userData->id)->orderBy('created_at', 'desc')->first();
            // dd($oldSession->ip_address, $request->ip(), $request->userAgent());
            if(!empty($oldSession) && ($oldSession->ip_address != $request->ip() && $oldSession->user_agent != $request->userAgent())){
                $oldSession->deleted_by = $userData->id;
                $oldSession->save();
                $oldSession->delete();
                PersonalAccessToken::where('tokenable_id', $userData->id)->delete();
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

            $new_sms = SmsRequest::where('phone', $input['phone'])->first();
            if(empty($new_sms)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Số điện thoại chưa có yêu cầu mã !',
                ], 404);
            }

            if($new_sms->is_used == 0){
                if($new_sms->code_expired < date('Y-m-d H:i:s', time()-60)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Mã đã hết hiệu lực !',
                    ], 400);
                }
            }

            $userCreate = User::create([
                'name' => $input['name'],
                'address' => $input['address'],
                'ward_id' => $input['ward_id'],
                'district_id' => $input['district_id'],
                'province_id' => $input['province_id'],
                'phone' => $input['phone'],
                'request_code_at' => date('Y-m-d H:i:s', time()+60),
                'password' => Hash::make($input['password']),
                'is_active' => 1,
            ]);

            $new_sms->is_used = 1;
            $new_sms->save();

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

            $clear_request = User::find($user->id);
            $clear_request->request_code_at = null;
            $clear_request->save();

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

    /**
     * Send code (SMS) to phone number
     *
     * @param string $phone
     * @param \App\Http\Validators\SMS\SMSValidator $validator
     * @return \Psr\Http\Message\StreamInterface $smsResponse or  null
     */
    public function sendSMS(Request $request, SMSValidator $validator){
    // public function sendSMS(string $phone, $msg, SMSValidator $validator){
        $input = $request->all();
        $validator->validate($input);
        $input['action'] = strtoupper($input['action']);

        try{
            DB::beginTransaction();

            $code = env('SMS_ENABLE') == 1 ? str_shuffle(''.mt_rand(10000000,99999999)) : '12345678';
            // $message = $code.$input['message'];
            switch($input['action']){
                case ACTION_SMS['register']:
                    $message = $code.' la ma xac minh dang ky Baotrixemay cua ban';
                    $check = SmsRequest::where('phone', $input['phone'])->first();
                    if(empty($check)){
                        SmsRequest::create([
                            'phone' => $input['phone'],
                            'code' => $code,
                            'code_expired' => date('Y-m-d H:i:s', time()),
                            'is_used' => 0,
                        ]);
                    }
                    else{
                        if($check->is_used == 0){
                            if($check->code_expired > date('Y-m-d H:i:s', time()-60)){
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Vui lòng đợi 1 phút để yêu cầu mã mới !',
                                ], 400);
                            }
                            else{
                                $check->code_expired = date('Y-m-d H:i:s', time());
                                $check->save();
                            }
                        }
                    }
                    break;

                default:
                    $message = $code.' la ma xac minh dang ky Baotrixemay cua ban';
                    $user = User::where('phone', $input['phone'])->where('is_active', 1)->first();
                    if(empty($user)){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Người dùng không tồn tại !',
                        ], 404);
                    }
                    if($user->request_code_at > date('Y-m-d H:i:s', time()-60)){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Vui lòng đợi 1 phút để yêu cầu mã mới !',
                        ], 400);
                    }

                    $user->password = Hash::make($code);
                    $user->request_code_at = date('Y-m-d H:i:s', time());
                    $user->save();
                    break;
            }

            $params = [
                "ApiKey" => env('SMS_KEY'),
                "SecretKey" => env('SMS_SECRET'),
                "Phone"=> $input['phone'],
                "Content" => $message,
                "SmsType" => 2,
                "Brandname" => env('SMS_BRAND_NAME', 'Baotrixemay'),
            ];

            $client = new Client();
            if(env('SMS_ENABLE') == 1){
                $smsResponse = $client->get(env('SMS_URL'), ['query' => $params])->getBody();
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
        return $smsResponse ?? response()->json([]);
    }
}
