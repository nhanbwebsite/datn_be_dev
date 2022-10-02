<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupPermission extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'group_permissions';
    protected $fillable = [
        'code',
        'name',
        'table_name',
        'is_active',
        'is_delete',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
