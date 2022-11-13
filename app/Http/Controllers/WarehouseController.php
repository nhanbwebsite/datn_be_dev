<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseCollection;
use App\Http\Resources\WarehouseResource;
use App\Http\Validators\Warehouse\WarehouseUpsertValidator;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WarehouseController extends Controller
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
            $data = Warehouse::where('is_active', !empty($input['is_active'] ) ? $input['is_active'] : 1)->where(function($query) use($input) {
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
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
            $resource = new WarehouseCollection($data);
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
    public function store(Request $request, WarehouseUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();

            $check = Warehouse::select('id')
            ->where('name', $input['name'])
            ->where('address', $input['address'])
            ->where('province_id', $input['province_id'])
            ->where('district_id', $input['district_id'])
            ->where('ward_id', $input['ward_id'])
            ->get();
            if(!$check->isEmpty()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kho đã tồn tại !',
                ], 400);
            }

            $warehouseCreate = Warehouse::create([
                'name' => $input['name'],
                'address' => $input['address'],
                'province_id' => $input['province_id'],
                'district_id' => $input['district_id'],
                'ward_id' => $input['ward_id'],
                'is_active' => $input['is_active'] ?? 1,
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
            'message' => 'Kho ['.$warehouseCreate->name.'] đã được tạo thành công !',
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
            $data = Warehouse::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy kho !',
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
            'data' => new WarehouseResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, WarehouseUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $user = $request->user();
            $warehouse = Warehouse::find($id);
            if(empty($warehouse)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kho không tồn tại !',
                ], 404);
            }

            $warehouse->name = $request->name ?? $warehouse->name;
            $warehouse->address = $request->address ?? $warehouse->address;
            $warehouse->province_id = $request->province_id ?? $warehouse->province_id;
            $warehouse->district_id = $request->district_id ?? $warehouse->province_id;
            $warehouse->ward_id = $request->ward_id ?? $warehouse->ward_id;
            $warehouse->is_active = $request->is_active ?? $warehouse->is_active;
            $warehouse->updated_by = $user->id;
            $warehouse->save();
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
            'message' => 'Đã cập nhật thông tin ['.$warehouse->name.'] !',
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
            if(!is_array($id)){
                $data = Warehouse::find($id);
                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Không tìm thấy kho !',
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
            'message' => 'Đã xóa kho ['.$data->name.'] !',
        ]);
    }
}
