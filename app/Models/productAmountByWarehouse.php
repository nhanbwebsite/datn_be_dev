<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class productAmountByWarehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'productAmountByWarehouse';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'variant_id',
        'pro_variant_id',
        'product_amount',
        'warehouse_id',
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

    public function product(){
        return $this->hasMany(Product::class, 'product_id', 'id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
