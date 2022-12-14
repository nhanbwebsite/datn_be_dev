<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShippingMethodClientCollection;
use App\Http\Resources\ShippingMethodCollection;
use App\Http\Resources\ShippingMethodResource;
use App\Http\Validators\ShippingMethod\ShippingMethodCreateValidator;
use App\Http\Validators\ShippingMethod\ShippingMethodUpdateValidator;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $input['limit'] = $request->limit ?? 10;
        try{
            $data = ShippingMethod::where('is_active', $input['is_active'] ?? 1)->where(function ($query) use ($input){
                if(!empty($input['code'])){
                    $query->where('code', $input['code']);
                }
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
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
        return response()->json(new ShippingMethodCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ShippingMethodCreateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();

            $create = ShippingMethod::create([
                'name' => $input['name'],
                'code' => strtoupper($input['code']),
                'is_active' => $input['is_active'] ?? 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            DB::commit();
        }
        catch(HttpException $e) {
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
            'message' => '???? th??m h??nh th???c v???n chuy???n ['.$create->name.'] th??nh c??ng !'
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
            $data = ShippingMethod::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'H??nh th???c v???n chuy???n kh??ng t???n t???i !'
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
            'data' => new ShippingMethodResource($data),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, ShippingMethodUpdateValidator $validator)
    {
        $input = $request->all();
        $validator->validate($input);
        try{
            DB::beginTransaction();

            $update = ShippingMethod::find($id);
            if(empty($update)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'H??nh th???c v???n chuy???n kh??ng t???n t???i !'
                ], 404);
            }

            $update->name = $input['name'] ?? $update->name;
            $update->is_active = $input['is_active'] ?? $update->is_active;
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
            'message' => '???? c???p nh???t h??nh th???c v???n chuy???n ['.$update->name.'] th??nh c??ng !',
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

            $delete = ShippingMethod::find($id);
            if(empty($delete)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'H??nh th???c v???n chuy???n kh??ng t???n t???i !'
                ], 404);
            }
            $delete->deleted_by = $user->id;
            $delete->save();
            $delete->delete();

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
            'message' => '???? x??a h??nh th???c v???n chuy???n ['.$delete->name.'] !',
        ]);
    }

    public function getClientShippingMethods()
    {
        try{
            $data = ShippingMethod::where('is_active', 1)->orderBy('created_at', 'desc')->get();
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
        return response()->json(new ShippingMethodClientCollection($data));
    }
}
