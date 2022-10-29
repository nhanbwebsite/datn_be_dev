<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressNote extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'address_notes';
    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'email',
        'province_id',
        'district_id',
        'ward_id',
        'is_default',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function province(){
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function district(){
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    public function ward(){
        return $this->hasOne(Ward::class, 'id', 'ward_id');
    }

    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
