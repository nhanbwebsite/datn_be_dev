<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileCollection;
use App\Http\Resources\FileResource;
use App\Http\Validators\File\FileUploadValidator;
use App\Models\File;
use Illuminate\Http\Request;
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
        $input['files'] = $request->file('files');
        $uploadValidator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();
            if(is_array($input['files'])){
                $url = [];
                foreach($input['files'] as $file){
                    $upload = $this->uploadFile($file);
                    if(!empty($upload)){
                        File::create([
                            'name' => $upload['name'],
                            'extension' => $upload['extension'],
                            'created_by' => $user->id,
                            'updated_by' => $user->id,
                        ]);

                        $url[] = env('FILE_URL').$upload['name'];
                    }
                    else{
                        throw new HttpException(400, 'Lỗi upload file');
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Upload file(s) thành công !',
                    'url' => $url,
                ]);
            }
            else{
                $upload = $this->uploadFile($input['files']);
                if(!empty($upload)){
                    File::create([
                        'name' => $upload['name'],
                        'extension' => $upload['extension'],
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ]);
                }
                else{
                    throw new HttpException(400, 'Lỗi upload file');
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Upload file(s) thành công !',
                    'url' => env('FILE_URL').$upload['name'],
                ]);
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
            $check = Storage::disk('public')->exists(PATH_UPLOAD.$data->name);
            if($check == false || empty($data)){
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
            $check = Storage::disk('public')->exists(PATH_UPLOAD.$data->name);
            if($check == false || empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy file !'
                ], 404);
            }

            $data->deleted_by = $user->id;
            $data->save();

            Storage::disk('public')->delete(PATH_UPLOAD.$data->name);
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
            if(!explode(' ', $fileOriginalName)){
                $fileOriginalName = Str::slug($fileOriginalName);
            }
            $fileOriginalExtension = $file->getClientOriginalExtension();
            if(!in_array($fileOriginalExtension, EXTENSION_UPLOAD)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Chỉ hỗ trợ định dạng: !'.implode(",", EXTENSION_UPLOAD),
                ]);
            }
            $checkFileExists = File::where('name', $fileOriginalName)->first();
            if(!empty($checkFileExists)){
                $fileOriginalName = explode('.', $fileOriginalName)[0].'_'.time().'.'.$fileOriginalExtension;
            }
            $file->storeAs(PATH_UPLOAD, $fileOriginalName, 'public');
            $fileData['name'] = $fileOriginalName ?? null;
            $fileData['extension'] = $fileOriginalExtension ?? null;
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

    public function viewFile($fileName){
        try{
            $data = File::where('name', $fileName)->first();
            if(empty($data)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy file !',
                ], 404);
            }
            // $file = public_path('/storage/images/'.$fileName);
            $file = Storage::disk('public')->get(PATH_UPLOAD.$fileName);
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
        return !empty($file) ? response($file)->header('Content-type', 'image/'.$data->extension) : null;
    }
}
