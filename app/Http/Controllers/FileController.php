<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileCollection;
use App\Http\Resources\FileResource;
use App\Http\Validators\File\FileCreateValidator;
use App\Http\Validators\File\FileUploadValidator;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = File::whereNull('deleted_at')->orderBy('created_at')->paginate(20);
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
        return response()->json(new FileCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FileUploadValidator $uploadValidator)
    {
        $input = $request->all();
        $uploadValidator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();

            foreach($input as $file){
                $upload = $this->uploadFile($file);
                if(!empty($upload)){
                    File::create([
                        'slug' => Str::slug($upload['name']),
                        'name' => $upload['name'],
                        'extension' => $upload['extension'],
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);
                }
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
            'message' => 'Upload file(s) thành công !',
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
            $data = File::find($id);
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy file !'
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
            'data' => new FileResource($data),
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
        //
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

            $data = File::find($id);
            return response()->json([
                'status' => 'error',
                'message' => 'File không tồn tại !'
            ], 404);
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
            'message' => 'Đã xóa ['.$data->name.'] !',
        ]);
    }

    public function uploadFile($file){
        try{
            $fileOriginalName = $file->getClientOriginalName();
            $fileOriginalExtension = $file->getClientOriginalExtension();
            $allow_ext = ['jpg', 'png', 'gif', 'jpeg'];
            if(!in_array($fileOriginalExtension, $allow_ext)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chỉ hỗ trợ định dạng: !'.implode(",", $allow_ext),
                ]);
            }

            $checkFileExists = File::where('name', $fileOriginalName)->first();
            if(!empty($checkFileExists)){
                $fileOriginalName = explode('.', $fileOriginalName)[0].'_'.time().'.'.$fileOriginalExtension;
            }

            if($file->move(public_path('images'), $fileOriginalName)){
                $fileData['name'] = $fileOriginalName ?? null;
                $fileData['extension'] = $fileOriginalExtension ?? null;
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

        return $fileData ?? null;
    }
}