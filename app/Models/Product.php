<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';
    protected $fillable = [
        'code',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'name',
        'slug',
        'description',
        'url_image',
        'price',
        'discount',
        'brand_id',
        'subcategory_id',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function inWarehouse(){
        return $this->hasMany(productAmountByWarehouse::class, 'product_id', 'id');
    }

    public function brand(){
        return $this->belongsTo(Brands::class, 'brand_id', 'id');
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public static function productVariants($id){
        // return $this->hasMany(ProductVariantDetail::class, 'product_id', 'id');
        $variantByProducts = DB::table(('productVariant'))->select('variants.id','variants.variant_name')
        ->join('products', 'productVariant.product_id', '=', 'products.id')
        ->join('variants', 'productVariant.variant_id', '=', 'variants.id')
        ->where('products.id',$id)
        ->get();
        return $variantByProducts;

        ;
        // return $this->belongsToMany(ProductVariantDetail::class, 'productVariant', 'product_id', 'variant_id');
    }

    public static function productsBySubCate($id){
        $proBySub = DB::table(('products'))
        ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
        ->where('sub_categories.id',$id)
        ->get();
        return $proBySub;
    }

    public static function variantDetailsProductByProId($id){
        $data = DB::table('products')->select('products.id as product_id','productVariantDetails.color_id','productVariantDetails.price','productVariantDetails.discount','colors.name as color_name','variants.variant_name','productVariant.variant_id')
        ->join('productVariant','products.id', 'productVariant.product_id')
        ->join('productVariantDetails','productVariant.id','productVariantDetails.pro_variant_id')
        ->join('variants','productVariant.variant_id','variants.id')
        ->join('colors','productVariantDetails.color_id','colors.id')
        ->where('products.id',$id)->get();
        return $data;
    }

    public static function variantProducAll(){
        $data = DB::table('products')->select('products.id as','productVariantDetails.color_id','productVariantDetails.price','productVariantDetails.discount','colors.name as color_name','variants.variant_name','productVariant.variant_id')
        ->join('productVariant','products.id', 'productVariant.product_id')
        ->join('productVariantDetails','productVariant.id','productVariantDetails.pro_variant_id')
        ->join('variants','productVariant.variant_id','variants.id')
        ->join('colors','productVariantDetails.color_id','colors.id')
        ->get();
        return $data;
    }

    // public static function variantDetailsProductByCategories($category_id){
    //     $data = DB::table('products')->select('products.id as product_id','productVariantDetails.color_id','productVariantDetails.price','productVariantDetails.discount','colors.name as color_name','variants.variant_name','productVariant.variant_id','categories.id as category_id','categories.name as categoryName')
    //     ->join('productVariant','products.id', 'productVariant.product_id')
    //     ->join('productVariantDetails','productVariant.id','productVariantDetails.pro_variant_id')
    //     ->join('variants','productVariant.variant_id','variants.id')
    //     ->join('colors','productVariantDetails.color_id','colors.id')
    //     ->join('sub_categories','products.subcategory_id','sub_categories.id')
    //     ->join('categories','sub_categories.category_id','categories.id')
    //     ->where('categories.id',$category_id)->get();
    //     return $data;
    // }

    public function productByCategory($category_id){
        $data = DB::table('categories')
        ->join('sub_categories','categories.id','sub_categories.category_id')
        ->join('products','sub_categories.id','products.subcategory_id')
        ->where('categories.id',$category_id)
        ->get();
        return $data;
    }
}
