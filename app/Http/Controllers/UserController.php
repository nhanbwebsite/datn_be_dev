<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
            $userCreate = User::create([
                'name' => $request->name,
                'address' => $request->address,
                'role_id' => $request->role_id ?? ROLE_ID_USER,
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
    public function update(Request $request, $id)
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            $validator = $this->upsertValidate($input);
            $user = User::find($id);
            if(empty($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng không tồn tại !',
                ], 404);
            }
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
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
            $user->updated_by = auth('sanctum')->user()->id;
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
            if(!is_array($id)){
                $data = User::find($id);
                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy người dùng !',
                    ], 404);
                }
                $data->is_delete = 1;
                $data->deleted_by = auth('sanctum')->user()->id;
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
            'message' => 'Đã xóa ['.$data->name.']',
        ]);
    }

    public function upsertValidate($input){
        if(!empty($input['id'])){
            // Update
            $rules = [
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255|email',
                'role_id'   => 'nullable|numeric|exists:roles,id',
                'store_id'   => 'nullable|numeric|exists:stores,id',
                'ward_id' => 'required|exists:wards,id',
                'district_id' => 'required|exists:districts,id',
                'province_id' => 'required|exists:provinces,id',
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
                'role_id.exists' => ':attribute không tồn tại !',
                'store_id.numeric' => ':attribute chưa đúng !',
                'store_id.exists' => ':attribute không tồn tại !',
                'ward_id.required' => ':attribute không được để trống !',
                'ward_id.exists' => ':attribute không tồn tại !',
                'district_id.required' => ':attribute không được để trống !',
                'district_id.exists' => ':attribute không tồn tại !',
                'province_id.required' => ':attribute không được để trống !',
                'province_id.exists' => ':attribute không tồn tại !',
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
                'store_id' => 'Cửa hàng',
                'ward_id' => 'Xã/Phường/Thị trấn',
                'district_id' => 'Quận/Huyện',
                'province_id' => 'Tỉnh/Thành phố',
                'password' => 'Mật khẩu',
                'is_active' => 'Trạng thái',
            ];
        }
        else{
            // Create
            $rules = [
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'role_id'   => 'nullable|numeric|exists:roles,id',
                'store_id'   => 'nullable|numeric|exists:stores,id',
                'ward_id' => 'required|exists:wards,id',
                'district_id' => 'required|exists:districts,id',
                'province_id' => 'required|exists:provinces,id',
                'phone' => 'required|string|min:10|regex:/^0[2-9]{1}[0-9]{8}$/',
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
                'role_id.exists' => ':attribute không tồn tại !',
                'store_id.numeric' => ':attribute chưa đúng !',
                'store_id.exists' => ':attribute không tồn tại !',
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
                'store_id' => 'Cửa hàng',
                'ward_id' => 'Xã/Phường/Thị trấn',
                'district_id' => 'Quận/Huyện',
                'province_id' => 'Tỉnh/Thành phố',
                'phone' => 'Số điện thoại',
                'password' => 'Mật khẩu',
                'is_active' => 'Trạng thái',
            ];
        }
        $v = Validator::make($input, $rules, $messages, $attributes);
        return $v;
    }
}
