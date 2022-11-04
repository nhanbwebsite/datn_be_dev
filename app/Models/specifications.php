<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class specifications extends Model
{
    use HasFactory;
    protected $table = 'specifications';
    protected $fillable = [
        'id_category',
        'infomation',
        'is_active',
        'deleted_by',
        'updated_by',
        'created_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
