<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class SubCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id',
        'name',
        'brand_id',
        'is_post',
        'slug',
        'url_img',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public static function AllSubByCate($id){
        // return $this->hasMany(ProductVariantDetail::class, 'product_id', 'id');
        $sybByCateId = DB::table(('categories'))
        ->join('sub_categories', 'categories.id', '=', 'sub_categories.category_id')
        ->where('categories.id',$id)
        ->get();
        return $sybByCateId; ;
        // return $this->belongsToMany(ProductVariantDetail::class, 'productVariant', 'product_id', 'variant_id');
    }


    public function posts(){
        return $this->hasMany(Post::class, 'subcategory_id', 'id');
    }
    public static function postByCategoryID($subcategory_id){
        $data = Post::where('subcategory_id',$subcategory_id)->get();
        return $data;
    }
    public function post(){
        return $this->hasMany(FooterContent::class, 'category_id', 'id');
    }
    public static function postViewByCategoryID($subcategory_id){
        $data = Post::where('subcategory_id',$subcategory_id)->orderBy('views','desc')->get();
        return $data;
    }
}


