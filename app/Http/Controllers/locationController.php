<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
class locationController extends Controller
{
    public function ProvinceAll(){
        //  lấy tất cả, không có phân trang
        $data = Province::all();
        return response()->json([
            'data' => $data
        ],200);
    }

    public function DistrictAll(){
        //  lấy tất cả quận huyện, không có phân trang
        $data = District::all();
        return response()->json([
            'data' => $data
        ],200);

    }

    public function WardAll(){
        //  lấy tất cả xã phường không có phân trang
        $data = Ward::all();
        return response()->json([
            'data' => $data
        ],200);

    }
}
