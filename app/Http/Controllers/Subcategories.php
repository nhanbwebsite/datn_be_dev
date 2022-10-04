<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Subcategories extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('subcategories')->all()->paginate(9);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fiedls = $request->validate([
            'category_id' =>'required',
            'name' =>'required|max:255',
            'slug' =>'required|max:255',
            'url_img' =>'required|max:300'
        ]);
        if($fiedls) {
            return SubCategory::create($request->all());
        }

        return response()->json([
            'message' => 'created subcategory error',
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
        $dataSubcategory = SubCategory::find($id);
        if($dataSubcategory){
            return response()->json([
                'message' => 'created subcategory successfully',
                'data' => $dataSubcategory
            ]);
        }
        return response()->json([
            'message' => 'created subcategory error',
        ]); //

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
        $fiedls = $request->validate([
            'category_id' =>'required',
            'name' =>'required',
            'slug' =>'required|max:255',
            'url_img' =>'required|max:300',
            'is_active' =>'required',
        ]);

        if($fiedls) {
            $dataUpdate = SubCategory::find($id);
            $dataUpdate->category_id = $fiedls['category_id'];
            $dataUpdate->name = $fiedls['name'];
            $dataUpdate->slug = $fiedls['slug'];
            $dataUpdate->url_img = $fiedls['url_img'];

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
