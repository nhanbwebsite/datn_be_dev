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
        $variantByProducts = DB::table(('productVariant'))
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
}
