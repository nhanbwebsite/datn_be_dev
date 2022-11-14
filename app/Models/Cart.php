<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'address',
        'ward_id',
        'district_id',
        'province_id',
        'phone',
        'email',
        'coupon_id',
        'discount',
        'fee_ship',
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

    public function addressNote(){
        return $this->belongsTo(AddressNote::class, 'address_note_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details(){
        return $this->hasMany(CartDetail::class, 'cart_id', 'id');
    }

    public function ward(){
        return $this->belongsTo(Ward::class, 'ward_id', 'id');
    }

    public function district(){
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function province(){
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function shippingMethod(){
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'id');
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
    // public function coupon(){
    //     return $this->hasOne(Product::class, 'id', 'address_note_id');
    // }

    // public function promotion(){
    //     return $this->hasOne(Product::class, 'id', 'address_note_id');
    // }
}
