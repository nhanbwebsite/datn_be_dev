<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressNoteCollection;
use App\Http\Resources\AddressNoteResource;
use App\Http\Validators\AddressNote\AddressNoteCreateValidator;
use App\Http\Validators\AddressNote\AddressNoteUpdateValidator;
use App\Models\AddressNote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AddressNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit;
        try{
            $data = AddressNote::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use ($input){
                if(!empty($input['user_id'])){
                    $query->where('user_id', $input['user_id']);
                }
                if(!empty($input['address'])){
                    $query->where('address', 'like', '%'.$input['address'].'%');
                }
                if(!empty($input['email'])){
                    $query->where('email', 'like', '%'.$input['email'].'%');
                }
                if(!empty($input['phone'])){
                    $query->where('phone', $input['phone']);
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
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
            $resource = new AddressNoteCollection($data);
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
     * Create a new record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Validators\AddressNote\AddressNoteCreateValidator  $validator
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AddressNoteCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $user = $request->user();
            $userData = User::find($user->id);
            $old_addressNote = AddressNote::where('is_active', 1)->where('user_id', $user->id)->get()->toArray();
            foreach($old_addressNote as $key => $value){
                if($value['phone'] == $request->phone && $value['address'] == $request->address && $value['province_id'] == $request->province_id && $value['district_id'] == $request->district_id && $value['ward_id'] == $request->ward_id){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Địa chỉ đã tồn tại !',
                    ], 400);
                }
            }
            AddressNote::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'email' => $request->email ?? null,
                'address' => $request->address,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'is_default' => !empty($userData->addressNote) ? 0 : 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
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
            'message' => 'Thêm địa chỉ mới thành công !',
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
            $data = AddressNote::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy địa chỉ !',
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
            'data' => new AddressNoteResource($data),
        ]);
    }

    /**
     * Update the specified record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, AddressNoteUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $user = $request->user();
            $old_addressNote = AddressNote::whereNotIn('id', [$id])->where('is_active', 1)->where('user_id', $user->id)->get()->toArray();
            foreach($old_addressNote as $key => $value){
                if($value['phone'] == $request->phone && $value['address'] == $request->address && $value['province_id'] == $request->province_id && $value['district_id'] == $request->district_id && $value['ward_id'] == $request->ward_id){
                    return response()->json([
                        'status' => 'errror',
                        'message' => 'Địa chỉ đã tồn tại !',
                    ], 400);
                }
            }
            $addressNote = AddressNote::find($id);
            if(empty($addressNote)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Địa chỉ không tồn tại !',
                ], 404);
            }

            if(!empty($request->is_default)){
                AddressNote::where('user_id', $user->id)->update([
                    'is_default' => 0
                ]);
            }

            $addressNote->phone = $request->phone ?? $addressNote->phone;
            $addressNote->email = $request->email ?? $addressNote->email;
            $addressNote->address = $request->address ?? $addressNote->address;
            $addressNote->province_id = $request->province_id ?? $addressNote->province_id;
            $addressNote->district_id = $request->district_id ?? $addressNote->province_id;
            $addressNote->ward_id = $request->ward_id ?? $addressNote->ward_id;
            $addressNote->is_default = $request->is_default ?? $addressNote->is_default;
            $addressNote->is_active = $request->is_active ?? $addressNote->is_active;
            $addressNote->updated_by = $user->id;
            $addressNote->save();
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
            'message' => 'Đã cập nhật địa chỉ !',
        ]);
    }

    /**
     * Remove the specified record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try{
            DB::beginTransaction();
            $user = $request->user();
            if(!is_array($id)){
                $data = AddressNote::find($id);
                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy địa chỉ !',
                    ], 404);
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
            'message' => 'Đã xóa địa chỉ !',
        ]);
    }

    public function getAddressNoteByCurrentUser(Request $request){
        try{
            $current_user_id = $request->user()->id;
            $data = AddressNote::where('user_id', $current_user_id)->where('is_active', 1)->paginate($request->limit ?? 10);
            if(empty($data)){
                return response()->json([
                    'data' => []
                ]);
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
        return response()->json(new AddressNoteCollection($data));
    }
}
