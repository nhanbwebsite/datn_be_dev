<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'warehouses';
    protected $fillable = [
        'name',
        'address',
        'ward_id',
        'district_id',
        'province_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_active',
    ];

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
