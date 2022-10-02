<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class Categories extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataCategories = Category::all()->paginate(5);
        return response()->json([
            'data' => $dataCategories
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
      $fiedls =  $request->validate([
            'name' =>'required|max:255',
            'slug' =>'required|max:255',
            'url_img' =>'required|max:300'
        ]);

      $category =  Category::create([
            'name' => $fiedls['name'],
            'slug' => $fiedls['slug'],
            'url_img'=>$fiedls['url_img']
        ]);

        return response()->json([
           'success' => 'Category created successfully',
           'data' => $category
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id)->first();
        return response()->json([
            'data' => $category
        ],200);
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
            'name' =>'required|max:255',
            'slug' =>'required|max:255',
        ]);
        $category =  Category::find($id)->first();
        $category->name = $fiedls['name'];
        $category->slug = $fiedls['slug'];
        $category->url_img = $fiedls['url_img'];
        $category->save();
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
