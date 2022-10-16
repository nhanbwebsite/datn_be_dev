<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';
    protected $fillable = [
        'meta_title',
        'meta_keywords',
        'meta_description',
        'name',
        'slug',
        'description',
        'url_image',
        'price',
        'promotion',
        'color_ids',
        'product_weight',
        'product_height',
        'product_width',
        'amount',
        'deleted_by',
        'brand_id',
        'store_id',
        'subcategories_id',
        'is_active',
    ];
}
