<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductVariantDetailById extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "productVariantDetails";
    protected $fillable = [
        'pro_variant_id',
        'color_id',
        'price',
        'discount',
        'quantity',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function product_variant(){
        return $this->belongsTo(Warehouse::class, 'pro_variant_id', 'id');
    }

    public function color(){
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
}
