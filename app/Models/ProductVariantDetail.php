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
        'color_id',
        'product_id',
        'quantity',
        'price',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
