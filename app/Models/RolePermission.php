<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RolePermission extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'role_permissions';
    protected $fillable = [
        'role_id',
        'permission_id',
        'is_active',
        'is_delete',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function permission(){
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }

    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
