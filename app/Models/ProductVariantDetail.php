<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductVariantDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "productVariant";
    protected $fillable = [
        'variant_id',
        'product_id',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function pro_variant(){
        return $this->hasOne(ProductVariantDetailById::class, 'pro_variant_id', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant_product(){
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }
}
