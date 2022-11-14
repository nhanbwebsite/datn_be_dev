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
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function permission(){
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
