<?php

namespace App\Http\Controllers;

use App\Http\Resources\PromotionCollection;
use App\Http\Resources\PromotionResource;
use App\Http\Validators\Promotion\PromotionCreateValidator;
use App\Http\Validators\Promotion\PromotionUpdateValidator;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = !empty($request->limit) && $request->limit > 0 ? $request->limit : 10;
        try{
            $data = Promotion::where('is_active', $input['is_active'] ?? 1)->where(function($query) use ($input) {
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['expired_date'])){
                    $query->whereDate('expired_date', '<=', $input['expired_date']);
                }
            })->orderBy('created_at', 'desc')->paginate($input['limit']);
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
        return response()->json(new PromotionCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PromotionCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();
            if($input['expired_date'] >= date('Y-m-d', time())){
                $input['is_active'] = 1;
            }
            $create = Promotion::create([
                'code' => strtoupper($input['code']),
                'name' => $input['name'],
                'expired_date' => $input['expired_date'],
                'is_active' => $input['is_active'] ?? 0,
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
            'message' => 'Đã tạo chương trình khuyến mãi ['.$create->name.'] !',
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
            $data = Promotion::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy chương trình khuyến mãi !',
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
            'data' => new PromotionResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PromotionUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();

            $data = Promotion::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chương trình khuyến mãi không tồn tại !',
                ], 404);
            }

            $data->name = $input['name'] ?? $data->name;
            $data->expired_date = $input['expired_date'] ?? $data->expired_date;
            $data->is_active = $input['is_active'] ?? $data->is_active;
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
            'message' => 'Đã cập nhật chương trình khuyến mãi ['.$data->name.'] !'
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

            $data = Promotion::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy chương trình khuyến mãi !',
                ], 404);
            }

            $data->deleted_by = $user->id;
            $data->save();
            $data->delete();

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
            'message' => 'Đã xóa chương trình khuyến mãi ['.$data->name.'] !'
        ]);
    }
}
