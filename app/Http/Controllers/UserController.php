<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            $data = User::where('is_active', 1)->where(function($query) use($input) {
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
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new UserCollection($data);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], 400);
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
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'role_id'   => 'nullable|numeric|exists:roles,id',
            'ward_id' => 'required|exists:wards,id',
            'district_id' => 'required|exists:districts,id',
            'province_id' => 'required|exists:provinces,id',
            'phone' => 'required|string|min:10|unique:users,phone|regex:/^0[2-9]{1}[0-9]{8}$/',
            'password' => 'required|string|min:8',
            'is_active' => 'numeric',
        ];

        $messages = [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'address.string' => ':attribute phải là chuỗi !',
            'address.max' => ':attribute tối đa 255 ký tự!',
            'role_id.numeric' => ':attribute chưa đúng !',
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
            'is_active.numeric' => ':attribute chưa đúng !',
        ];

        $attributes = [
            'name' => 'Họ tên',
            'address' => 'Địa chỉ',
            'role_id' => 'Vai trò',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
            'is_active' => 'Trạng thái',
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
                'role_id' => $request->role_id,
                'ward_id' => $request->ward_id,
                'district_id' => $request->district_id,
                'province_id' => $request->province_id,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active ?? 0,
                'created_by' => auth('sanctum')->user()->id,
                'updated_by' => auth('sanctum')->user()->id,
            ]);
            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => '['.$userCreate->name.'] đã được tạo thành công !',
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
                    'message' => 'Không tìm thấy người dùng !',
                ], 404);
            }
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], 400);
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
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255|email',
            'role_id'   => 'nullable|numeric',
            'ward_id' => 'required',
            'district_id' => 'required',
            'province_id' => 'required',
            'password' => 'required|string|min:8',
            'is_active' => 'required|numeric',
        ];

        $messages = [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'address.string' => ':attribute phải là chuỗi !',
            'address.max' => ':attribute tối đa 255 ký tự!',
            'email.string' => ':attribute phải là chuỗi !',
            'email.email' => ':attribute chưa đúng định dạng ! VD: duynh123@gmail.com',
            'email.max' => ':attribute tối đa 255 ký tự !',
            'role_id.numeric' => ':attribute chưa đúng !',
            'ward_id.required' => ':attribute không được để trống !',
            'district_id.required' => ':attribute không được để trống !',
            'province_id.required' => ':attribute không được để trống !',
            'password.required' => ':attribute không được để trống !',
            'password.string' => ':attribute phải là chuỗi !',
            'password.min' => ':attribute tối thiểu 8 ký tự !',
            'is_active.required' => ':attribute không để trống !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];

        $attributes = [
            'name' => 'Họ tên',
            'address' => 'Địa chỉ',
            'email' => 'Email',
            'role_id' => 'Vai trò',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'password' => 'Mật khẩu',
            'is_active' => 'Trạng thái',
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
            $user = User::find($id);
            if(empty($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại !',
                ], 404);
;           }

            $user->name = $request->name ?? $user->name;
            $user->address = $request->address ?? $user->address;
            $user->email = $request->email ?? $user->email;
            $user->role_id = $request->role_id ?? $user->role_id;
            $user->ward_id = $request->ward_id ?? $user->ward_id;
            $user->district_id = $request->district_id ?? $user->district_id;
            $user->province_id = $request->province_id ?? $user->province_id;
            $user->password = Hash::make($request->password ?? $user->password);
            $user->is_active = $request->is_active ?? $user->is_active;
            $user->updated_by = auth('sanctum')->user()->id;
            $user->save();

            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng ['.$user->name.'] đã được cập nhật !',
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
            $data = User::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy người dùng !',
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
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa ['.$data->name.']',
        ]);
    }
}
