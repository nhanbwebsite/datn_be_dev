<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'cart_details';
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'color_id',
        'price',
        'quantity',
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

    public function cart(){
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant(){
        return $this->belongsTo(ProductVariantDetail::class, 'variant_id', 'id');
    }
}
