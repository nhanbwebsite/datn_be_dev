<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'code',
        'user_id',
        'address_note_id',
        'total',
        'discount',
        'coupon_id',
        'promotion_id',
        'fee_ship',
        'payment_method_id',
        'shipping_method_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details(){
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function addressNote(){
        return $this->belongsTo(AddressNote::class, 'address_note_id', 'id');
    }

    public function getStatus(){
        return $this->belongsTo(OrderStatus::class, 'status', 'id');
    }

    public function getPaymentMetyhod(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function shippingMethod(){
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'id');
    }

    // public function coupon(){
    //     return $this->hasOne(AddressNote::class, 'id', 'address_note_id');
    // }

    // public function promotion(){
    //     return $this->hasOne(AddressNote::class, 'id', 'address_note_id');
    // }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
