<?php

namespace App\Http\Controllers;

use App\Models\specifications as ModelsSpecifications;
use Illuminate\Http\Request;

class specifications extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data = ModelsSpecifications::all();
       return response()->json([
            'status' => 'success',
            'data' => json_decode($data)
       ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // test nên chưa  validation
        $infomations = $request->infomation;

        ModelsSpecifications::create([
            "id_category" => 29,
            "infomation" => json_encode($infomations)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "interface inserted successfully",
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
