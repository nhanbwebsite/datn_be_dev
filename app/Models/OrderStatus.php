<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'order_status';
    protected $fillable = [
        'name',
        'code',
        'sort_level',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_active',
        'is_delete',
    ];

    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
