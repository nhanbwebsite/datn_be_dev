<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stores';
    protected $fillable = [
        'name',
        'slug',
        'warehouse_id',
        'address',
        'ward_id',
        'district_id',
        'province_id',
        'is_active',
        'deleted_by',
        'updated_by',
        'created_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function ward(){
        return $this->hasOne(Ward::class, 'id', 'ward_id');
    }

    public function district(){
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    public function province(){
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
