<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Slideshow_detail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'slideshow_details';
    protected $fillable = [
        'id',
        'slideshow_id',
        'image',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
