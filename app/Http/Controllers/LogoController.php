<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LogoController extends Controller
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
            $data = Logo::all();
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
        return response()->json($data);
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
        $user = $request->user();
        try {
            DB::beginTransaction();
            $data = Logo::create([
                'image'=> $input['image'] ?? null,
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ]);
            DB::commit();
        }catch(HttpException $e){
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
            'message' => ' đã được tạo thành công !',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logo  $logo
     * @return \Illuminate\Http\Response
     */

        public function show($id)
        {
            try{
                $data = Logo::find($id);
                if(empty($data)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'không tồn tại !'
                    ], 404);
                }

                DB::commit();
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
                'data' => $data,
            ]);
        }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logo  $logo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = Logo::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => ' không tồn tại !'
                ], 404);
            }
            $data->image = $input['image'] ?? $data->image;
            $data->is_active = $input['is_active'] ?? $data->is_active;
            $data->updated_by = $user->id;
            $data->save();

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
            'message' =>'Đã cập nhật  !',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logo  $logo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logo $logo)
    {
        //
    }
}
