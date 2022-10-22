<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CityController extends Controller
{
    /**
     * Get all provices
     *
     * @return Province
     */
    public function index(){
        try{
            $data = Province::all();
            if(empty($data)){
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                ]);
            }
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getDistrict($province_id){
        try{
            $province = Province::find($province_id);
            $data = $province->districts;
            if(empty($province) || empty($data)){
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                ]);
            }
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getWard($province_id, $district_id){
        try{
            $district = District::find($district_id);
            $data = $district->wards;
            if(empty($district) || empty($data)){
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                ]);
            }
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
