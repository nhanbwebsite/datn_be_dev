<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'logo';
    protected $fillable = [
        'id',
        'image',
        'is_active',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
        'deleted_at',
    ];
}
