<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategories extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'post_categories';
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function posts(){
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
