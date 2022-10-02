<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'ward_id',
        'district_id',
        'province_id',
        'role_id',
        'avatar',
        'verified_at',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_active',
        'is_delete',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function ward(){
        return $this->hasOne(Ward::class, 'id', 'ward_id');
    }

    public function district(){
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    public function province(){
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function session(){
        return $this->belongsTo(UserSession::class, 'id', 'user_id');
    }
}
