<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubCategory extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;
    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'url_img',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
