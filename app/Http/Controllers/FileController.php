<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileCollection;
use App\Http\Resources\FileResource;
use App\Http\Validators\File\FileUploadValidator;
use App\Models\File;
use App\Models\Logo;
use App\Models\Product;
use App\Models\Slideshow;
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
            $data = File::whereNull('deleted_at')->orderBy('created_at', 'desc')->paginate(20);
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
        $uploadValidator->validate($request->all());
        $url = [];
        try{
            DB::beginTransaction();
            if(is_array($request->file('files'))){
                foreach($request->file('files') as $file){
                    $extension = $file->getClientOriginalExtension();
                    if(round(($file->getSize()/1024)/1024, 4) > 20){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'File vượt quá 20MB !',
                        ], 422);
                    }
                    if(!in_array(strtolower($extension), EXTENSION_UPLOAD)){
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Chỉ hỗ trợ định dạng '. implode(", ", EXTENSION_UPLOAD),
                        ], 422);
                    }

                    $fileName = Str::slug(explode(".", $file->getClientOriginalName())[0]) . '_' . time() . '.' . $extension;
                    $file->storeAs(PATH_UPLOAD, $fileName, 'public');

                    $create = File::create([
                        'name' => $fileName,
                        'extension' => $extension,
                        'created_by' => $request->user()->id,
                        'updated_by' => $request->user()->id,
                    ]);

                    $url[] = env('FILE_URL').$create->name;
                }
            }
            else{
                $file = $request->file('files');
                $extension = $file->getClientOriginalExtension();
                if(round(($file->getSize()/1024)/1024, 4) > 20){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'File vượt quá 20MB !',
                    ], 422);
                }
                if(!in_array(strtolower($extension), EXTENSION_UPLOAD)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Chỉ hỗ trợ định dạng '. implode(", ", EXTENSION_UPLOAD),
                    ], 422);
                }

                $fileName = Str::slug(explode(".", $file->getClientOriginalName())[0]) . '_' . time() . '.' . $extension;
                $file->storeAs(PATH_UPLOAD, $fileName, 'public');

                $create = File::create([
                    'name' => $fileName,
                    'extension' => $extension,
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);

                $url[] = env('FILE_URL').$create->name;
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
            'url' => $url ?? null,
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
        return !empty($file) ? response($file)->header('Content-type', 'image/'.strtolower($data->extension)) : null;
    }

    public function deleteFiles(Request $request){
        $user = $request->user();
        $names = $request->names;
        try{
            DB::beginTransaction();

            if(!is_array($names)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tên ảnh không đúng !',
                ], 422);
            }

            $errors = [];
            foreach($names as $key => $item){
                $productHasImage = Product::where('url_image', 'like', '%'.$item.'%')->count();
                $slideHasImage = Slideshow::where('image', 'like', '%'.$item.'%')->count();
                $logoHasImage = Logo::where('image', 'like', '%'.$item.'%')->count();

                if($productHasImage > 0 || $slideHasImage > 0 || $logoHasImage > 0){
                    $errors[] = '['.$item.'] đang được sử dụng, không thể xóa !';
                }
                else{
                    $check = Storage::disk('public')->exists(PATH_UPLOAD.$item);
                    $file = File::where('name', $item)->first();
                    if(!empty($file) && $check == true){
                        $file->deleted_by = $user->id;
                        $file->save();
                        Storage::disk('public')->delete(PATH_UPLOAD.$item);
                        $file->delete();
                    }
                }
            }

            if(count($errors) > 0){
                return response()->json([
                    'status' => 'error',
                    'message' => $errors,
                ], 400);
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
            'message' => 'Đã xóa !',
        ]);
    }
}
