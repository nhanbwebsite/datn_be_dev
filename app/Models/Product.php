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
        'discount',
        // 'color_ids',
        // 'product_weight',
        // 'product_height',
        // 'product_width',
        'deleted_by',
        'brand_id',
        'warehouse_id',
        'subcategories_id',
        'is_active',
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
}
