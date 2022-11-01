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

    public function addressNote(){
        return $this->hasOne(AddressNote::class, 'id', 'address_note_id');
    }

    public function getStatus(){
        return $this->hasOne(OrderStatus::class, 'id', 'status');
    }

    public function paymentMethod(){
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    // public function shippingMethod(){
    //     return $this->hasOne(AddressNote::class, 'id', 'address_note_id');
    // }

    // public function coupon(){
    //     return $this->hasOne(AddressNote::class, 'id', 'address_note_id');
    // }

    // public function promotion(){
    //     return $this->hasOne(AddressNote::class, 'id', 'address_note_id');
    // }

    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
