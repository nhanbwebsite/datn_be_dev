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
        'subcategories_id',
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
        return $this->belongsTo(Subcategory::class, 'subcategories_id', 'id');
    }
}
