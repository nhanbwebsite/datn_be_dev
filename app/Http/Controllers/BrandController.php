<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $data = Brands::paginate(9);
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch(Exception $e){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'brand_name' => 'required|min:2|max:255',
        ];

        $messages = [
            'brand_name.required' => ':attribute không được để trống',
            'brand_name.min' => ':attribute tối thiểu 2 kí tự ',
            'brand_name.max' => ':attribute tối đa 255 kí tự'
        ];

        $attributes = [
            'brand_name' => 'Tên nhà cung cấp',
        ];

        try {

            $validator = Validator::make($request->only('brand_name'), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }

          $create =   Brands::create([
                'brand_name' => $request->brand_name,
                'slug' => Str::slug($request->brand_name)
            ]);

            if($create) {
                return response()->json([
                    'status' => 'Created successfully',
                    'data =>' => $create
                ]);
            }

        } catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]); //
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
        try {
            $data = Brands::find($id);
            if(empty($data)) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Not Found'
                ]); //
            }

            return response()->json([
                'status' => 'Successfully',
                'data' => $data
            ]);


        } catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
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
        $rules = [
            'brand_name' => 'required|min:2|max:255',
        ];

        $messages = [
            'brand_name.required' => ':attribute không được để trống',
            'brand_name.min' => ':attribute tối thiểu 2 kí tự ',
            'brand_name.max' => ':attribute tối đa 255 kí tự'
        ];

        $attributes = [
            'brand_name' => 'Tên nhà cung cấp',
        ];

        try {

            $validator = Validator::make($request->only('brand_name'), $rules, $messages, $attributes);
            if($validator->fails()){
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ], 422);
            }
          $data = Brands::find($id);
          $data->update([
                'brand_name' => $request->brand_name,
                'slug' => Str::slug($request->brand_name),
                'is_active' => $request->is_active,
            ]);

            if($data) {
                return response()->json([
                    'status' => 'Created successfully',
                    'data =>' => $data
                ]);
            }

        } catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]); //
        }
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
