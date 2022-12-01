<?php

namespace App\Http\Controllers;

use App\Http\Validators\FooterCategory\FooterCategoryUpsertValidator;
use App\Http\Resources\FooterCategoryCollection;
use App\Http\Resources\FooterCategoryResource;
use App\Http\Resources\LoadFooterContentResource;
use App\Models\FooterCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class FooterCategoryController extends Controller
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
            $data = FooterCategory::where('is_active', !empty($input['is_active']) ? $input['is_active'] : 1)->where(function($query) use($input) {
                if(!empty($input['name'])){
                    $query->where('name', 'like', '%'.$input['name'].'%');
                }
                if(!empty($input['is_active'])){
                    $query->where('is_active', $input['is_active']);
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
        return response()->json(new FooterCategoryCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,FooterCategoryUpsertValidator $validator)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']) ?? null;
        $validator->validate($input);
        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = FooterCategory::create([
                'name' => mb_strtoupper(mb_substr($input['name'], 0, 1)).mb_substr($input['name'], 1),
                'slug' => $input['slug'],
                'is_active' => $input['is_active'] ?? 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            DB::commit();
        } catch(HttpException $e) {
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
            'message' => '['.$data->name . '] đã được tạo thành công !',
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryFooter  $CategoryFooter
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $data = FooterCategory::find($id);

            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Danh mục không tồn tại !'
                ],404);
            }
        } catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'data' => new FooterCategoryResource($data),
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryFooter  $CategoryFooter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FooterCategoryUpsertValidator $validator)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']) ?? null;
        $validator->validate($input);
        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = FooterCategory::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Danh mục bài viết không tồn tại !'
                ], 404);
            }
            $data->update([
                'name' => mb_strtoupper(mb_substr($input['name'], 0, 1)).mb_substr($input['name'], 1),
                'slug' => $input['slug'],
                'updated_by' => $user->id,
            ]);

            DB::commit();
        } catch(HttpException $e) {
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
            'message' => 'Danh mục đã được cập nhật thành '.$data->name.'!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryFooter  $CategoryFooter
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        try {
            DB::beginTransaction();

            $data = FooterCategory::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Danh mục không tồn tại !',
                ], 404);

            }
            $data->deleted_by = $user->id;
            $data->save();
            $data->delete();

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
            'message' => 'Đã xóa thành công danh mục ' . $data->name
        ]);
    }

    public function loadByCate($id)
    {
        try{
            $data = FooterCategory::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Danh mục không tồn tại, vui lòng kiểm tra lại'
                ], 404);
            }
        } catch(HttpException $e){
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
            'data' => new LoadFooterContentResource($data),
        ]);
    }
}
