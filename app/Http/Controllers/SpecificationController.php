<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpecificationCollection;
use App\Http\Resources\SpecificationResource;
use App\Http\Validators\Specification\SpecificationUpsertValidator;
use App\Models\Specification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        try{
            $data = Specification::where('is_active', $input['is_active'] ?? 1)->where(function($query) use ($input){
                if(!empty($input['id_category'])){
                    $query->where('id_category', $input['id_category']);
                }
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
            })->orderBy('created_at', 'desc')->paginate(!empty($input['limit']) ? $input['limit'] : 10);
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
        return response()->json(new SpecificationCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SpecificationUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $user = $request->user();
            $specificationCreate = Specification::create([
                'id_category' => $input['id_category'],
                'name' => $input['name'],
                'infomation' => json_encode($input['infomation']),
                'is_active' => $input['is_active'] ?? 1,
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
            'message' => 'Đã tạo ['.$specificationCreate->name.'] thành công !',
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
            $data = Specification::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy thông số !',
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
            'data' => new SpecificationResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, SpecificationUpsertValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();
            $user = $request->user();
            $update = Specification::find($id);
            if(empty($update)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thông số không tồn tại !',
                ], 404);
            }

            $update->id_category = $input['id_category'] ?? $update->id_category;
            $update->name = $input['name'] ?? $update->name;
            $update->infomation = !empty($input['infomation']) ? json_encode($input['infomation']) : $update->infomation;
            $update->is_active = $input['is_active'] ?? $update->is_active;
            $update->updated_by = $user->id;
            $update->save();

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
            'message' => 'Đã cập nhật thông số ['.$update->name.'] !',
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
            $user = $request->user();
            DB::beginTransaction();

            $del = Specification::find($id);
            if(empty($del)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thông số không tồn tại !',
                ], 404);
            }

            $del->deleted_by = $user->id;
            $del->save();
            $del->delete();

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
            'message' => 'Đã xóa thông số ['.$del->name.'] !'
        ]);
    }
}
