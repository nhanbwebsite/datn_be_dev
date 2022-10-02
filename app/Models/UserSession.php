<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSession extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'user_sessions';
    protected $fillable = [
        'user_id',
        'token',
        'expired',
        'ip_address',
        'user_agent',
        'is_delete',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
