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
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
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

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

}
