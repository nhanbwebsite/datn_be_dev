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
        'product_id',
        'content',
        'is_active',
        'created_at',
        'deleted_at',
        'updated_at',
        'created_by',
        'deleted_by',
        'updated_by',
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function post(){
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function parentCmt($id){
        $data = Comment::where('id', $id)->where('is_active', 1)->whereNull('deleted_at')->first();
        return $data ?? null;
    }
}
