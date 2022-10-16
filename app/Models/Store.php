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
        'store_name',
        'slug',
        'is_active',
        'delete_by',
        'update_by',
        'create_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

}
