<?php

namespace App\Http\Controllers;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FileCreateValidator $validator, FileUploadValidator $uploadValidator)
    {
        $input = $request->all();
        if($uploadValidator->validate($input)){
            $input['slug'] = Str::slug($request->file('files')->getClientOriginalName());
            $input['name'] = $request->file('files')->getClientOriginalName();
            $input['extension'] = $request->file('files')->getClientOriginalExtension();
        }
        $validator->validate($input);
        $user = $request->user();
        try{
            DB::beginTransaction();

            $create = File::create([
                'slug' => Str::slug($input['file_name']),
                'name' => $input['name'],
                'extension' => $input['extension'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            if($create){
                if(count($input['files']) == 1){
                    dd($input['files']);
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
        //
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
    public function destroy($id)
    {
        //
    }
}
