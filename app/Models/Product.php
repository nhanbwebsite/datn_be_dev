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
        'collection_images',
        'price',
        'discount',
        'specification_infomation',
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

    // public function brand(){
    //     return $this->belongsTo(Brands::class, 'brand_id', 'id');
    // }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public static function category($iPro){
        $data = DB::table('products')->select('categories.id as cartegory_id','categories.name as category_name')
        ->join('sub_categories','products.subcategory_id','sub_categories.id')
        ->join('categories','sub_categories.category_id','categories.id')
        ->where('products.id',$iPro)
        ->first();
        return $data;
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'product_id', 'id');
    }


    public static function productVariants($id){
        // return $this->hasMany(ProductVariantDetail::class, 'product_id', 'id');
        $variantByProducts = DB::table(('productVariant'))->select('variants.id','variants.variant_name')
        ->join('products', 'productVariant.product_id', '=', 'products.id')
        ->join('variants', 'productVariant.variant_id', '=', 'variants.id')
        ->where('productVariant.is_active',1)
        ->where('productVariant.deleted_at',null)
        ->where('products.id',$id)
        ->get();
        return $variantByProducts;
        ;
        // return $this->belongsToMany(ProductVariantDetail::class, 'productVariant', 'product_id', 'variant_id');
    }

    public static function productsBySubCate($id){
        $proBySub = DB::table('products')
        ->select('products.id as product_id','products.slug','sub_categories.id as subcategory_id','products.code','products.meta_title','products.meta_keywords','products.meta_description','products.name as product_name','products.description','products.url_image')
        ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
        ->where('products.subcategory_id',$id)
        ->orderByDesc('products.id')->get();
        // ->paginate(4);
        return $proBySub;
    }

    public static function variantDetailsByProvariant($proVariantId){
        $data = DB::table('productVariantDetails')
        ->where('productVariantDetails.pro_variant_id',$proVariantId)
        ->get();
        return $data;
    }

    public static function variantDetailsProductByProId($id){
        $data = DB::table('products')->select('products.id as product_id','productVariantDetails.color_id','productVariantDetails.price','productVariantDetails.discount','productVariantDetails.quantity','colors.name as color_name','colors.color_code','variants.variant_name','productVariant.variant_id','productVariantDetails.id as product_variant_detail_id','productVariantDetails.deleted_at as delete_tails')
        ->join('productVariant','products.id', 'productVariant.product_id')
        ->join('productVariantDetails','productVariant.id','productVariantDetails.pro_variant_id')
        ->join('variants','productVariant.variant_id','variants.id')
        ->join('colors','productVariantDetails.color_id','colors.id')
        ->where('productVariantDetails.is_active',1)
        ->where('productVariantDetails.deleted_at',null)
        ->where('products.id',$id)->get();
        return $data;
    }

    public static function variantProducAll(){
        $data = DB::table('products')->select('products.id as','productVariantDetails.color_id','productVariantDetails.price','productVariantDetails.discount','colors.name as color_name','colors.color_code','variants.variant_name','productVariant.variant_id')
        ->join('productVariant','products.id', 'productVariant.product_id')
        ->join('productVariantDetails','productVariant.id','productVariantDetails.pro_variant_id')
        ->join('variants','productVariant.variant_id','variants.id')
        ->join('colors','productVariantDetails.color_id','colors.id')
        ->where('productVariant.is_active',"=",'NULL')
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

    //  tìm sản phẩm

    public function productByKeywords($keywords){
        $data = DB::table('products')
        ->select('products.id','products.name as product_name','productVariantDetails.price','products.slug','productVariantDetails.discount','products.description','products.url_image','products.subcategory_id','productVariantDetails.quantity','variants.variant_name','variants.slug as variant_slug ','colors.name as color_name','colors.slug as color_slug')
        // ->join('productAmountByWarehouse','products.id','productAmountByWarehouse.product_id')
        ->join('productVariant','products.id','productVariant.product_id')
        ->join('productVariantDetails','productVariant.id','productVariantDetails.pro_variant_id')
        ->join('variants','productVariant.variant_id','variants.id')
        ->join('colors','productVariantDetails.color_id','colors.id')
        ->where('products.name','like','%'.$keywords.'%')
        ->orWhere('products.meta_title','like','%'.$keywords.'%')
        ->get();
        return $data;
    }

    public static function getNameCreated($id){
        $data = DB::table('products')->select('users.name as created_by_name')
                ->join('users','products.created_by','users.id')->first();
        return $data;
    }

    public static function getAllSubcate(){
        $data = DB::table('sub_categories')
        ->select('sub_categories.id','sub_categories.category_id','sub_categories.name','sub_categories.slug','sub_categories.brand_id','sub_categories.url_img')
                ->get();
        return $data;
    }

    public static function getAllBrans(){
        $data = DB::table('brands')
        ->select('brands.id','brands.brand_name','brands.slug','sub_categories.id as sub_category_id','sub_categories.name as sub_category_name')
        ->join('sub_categories','brands.id','sub_categories.brand_id')
        ->where('is_post',0)
        ->get();
        return $data;
    }

    public static function productsByBrand($id){
        $proBySub = DB::table('products')
        ->select('products.id as product_id','products.slug','sub_categories.id as subcategory_id','products.code','products.meta_title','products.meta_keywords','products.meta_description','products.name as product_name','products.description','products.url_image')
        ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
        ->join('brands', 'sub_categories.brand_id', '=', 'brands.id')
        ->where('brands.id',$id)
        ->orderByDesc('products.id')->get();
        // ->paginate(4);
        return $proBySub;
    }

    public static function AllProductsByBrand(){
        $proBySub = DB::table('products')
        ->select('products.id as product_id','products.slug','sub_categories.id as subcategory_id','products.code','products.meta_title','products.meta_keywords','products.meta_description','products.name as product_name','products.description','products.url_image')
        ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
        ->join('brands', 'sub_categories.brand_id', '=', 'brands.id')
        ->orderByDesc('products.id')->get();
        // ->paginate(4);
        return $proBySub;
    }

    public function AllProductByCategory(){
        $data = DB::table('products')
        ->select('products.id as product_id','products.name','products.slug','products.subcategory_id')
        ->join('sub_categories','products.subcategory_id','sub_categories.id')
        ->get();
        return $data;
    }

    public static function AllCategory(){
        $data = DB::table('categories')
        ->select('categories.id as category_id','categories.name')
        ->where('is_post',0)
        ->get();
        return $data;
    }

    public static function AllSubCategoryByCategoryId($id){
        $data = DB::table('products')
        ->select('products.id','products.name','products.slug','products.url_image','sub_categories.id as sub_category_id','sub_categories.name as sub_category_name','categories.id as category_id','categories.name as category_name')
        ->join('sub_categories','products.subcategory_id','sub_categories.id')
        ->join('categories','sub_categories.category_id','categories.id')
        ->where('categories.is_post',0)
        ->where('categories.id',$id)
        ->get();
        return $data;
    }



    public static function getColorById($id){
        $data = DB::table('colors')
                ->where('colors.id',$id)->first();
        return $data;
    }

    public function getProductByWardHouse($wardhouse_id){

    }

}
