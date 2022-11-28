<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    // public $timestamps = false;
    protected $fillable = [
        'category_id',
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
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $table = 'posts';

    public function catePost() {
        return $this->hasOne(PostCategories::class, 'id','category_id');
    }
    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
