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
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function permission(){
        return $this->hasMany(Permission::class, 'group_id', 'id');
    }
}
