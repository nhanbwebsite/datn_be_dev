<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'comments';
    protected $fillable = [
        'parent_id',
        'user_id',
        'post_id',
        'content',
        'is_active',
        'is_delete',
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'deleted_by',
        'updated_by',
    ];

    public function createdBy(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function post(){
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function parentCmt($id){
        $data = Comment::where('id', $id)->where('is_active', 1)->whereNull('deleted_at')->first();
        return $data ?? null;
    }
}
