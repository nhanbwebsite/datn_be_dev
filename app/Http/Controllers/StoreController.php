<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreCollection;
use App\Http\Resources\StoreResource;
use App\Http\Validators\Store\StoreUpsertValidator;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreController extends Controller
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
        try {
            $data = Store::where('is_active', $input['is_active'] ?? 1)->where(function ($query) use ($input){
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['slug'])){
                    $query->where('slug', $input['slug']);
                }
                if(!empty($input['address'])){
                    $query->where('address', 'like', '%'.$input['address'].'%');
                }
                if(!empty($input['ward_id'])){
                    $query->where('ward_id', $input['ward_id']);
                }
                if(!empty($input['district_id'])){
                    $query->where('district_id', $input['district_id']);
                }
                if(!empty($input['province_id'])){
                    $query->where('province_id', $input['province_id']);
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
        } catch(HttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json(new StoreCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StoreUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $user = $request->user();
            $create = Store::create([
                'name' => $request->name,
                'slug' => $request->slug ?? Str::slug($request->name),
                'address' => $request->address,
                'ward_id' => $request->ward_id,
                'district_id' => $request->district_id,
                'province_id' => $request->province_id,
                'is_active' => $request->is_active ?? 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
            DB::commit();
        } catch(HttpException $e){
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
            'message' => 'Đã thêm thành công cửa hàng ' ."[$create->name]",
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
        try {
            $data = Store::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy cửa hàng phù hợp, vui lòng kiểm tra lại !'
                ], 404);
            }
        }catch(HttpException $e){
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
            'data' => new StoreResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, StoreUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $user = $request->user();

            $update = Store::find($id);
            if(empty($update)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy cửa hàng !'
                ], 404);
            }
            $update->name = $request->name ?? $update->name;
            $update->slug = $request->slug ?? Str::slug($request->name) ?? $update->slug;
            $update->address = $request->address ?? $update->address;
            $update->ward_id = $request->ward_id ?? $update->ward_id;
            $update->district_id = $request->district_id ?? $update->district_id;
            $update->province_id = $request->province_id ?? $update->province_id;
            $update->is_active = $request->is_active ?? $update->is_active;
            $update->updated_by = $user->id;
            $update->save();

            DB::commit();
        } catch(HttpException $e) {
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
            'status' => 'successfully',
            'message' => 'Đã cập nhật cửa hàng ['. $update->name .'] thành công !'
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
        try {
            DB::beginTransaction();
            $user = $request->user();
            $data = Store::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cửa hàng không tồn tại, vui lòng kiểm tra lại !',
                ], 404);
            }
            $data->deleted_by = $user->id;
            $data->save();
            $data->delete();
            DB::commit();
        } catch(HttpException $e ) {
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
            'status' => 'successfully',
            'message' => 'Đã xóa thành công cửa hàng '. "[$data->name]"
        ]);
    }
}
