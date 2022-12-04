<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'posts';
    protected $fillable = [
        'id',
        'subcategory_id',
        'user_id',
        'title',
        'short_des',
        'content_post',
        'image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'slug',
        'views',
        'is_active',
        'is_feature',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function subcategory() {
        return $this->belongsTo(SubCategory::class, 'subcategory_id','id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
