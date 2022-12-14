<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'coupons';
    protected $fillable = [
        'code',
        'type',
        'discount_value',
        'max_use',
        'status',
        'promotion_id',
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

    public function promotion(){
        return $this->belongsTo(Promotion::class, 'promotion_id', 'id');
    }

    public function used(){
        return $this->hasMany(CouponOrder::class, 'coupon_id', 'id');
    }
}
