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
        'id',
        'product_id',
        'user_id',
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

    public function product(){
        return $this->belongsTo(Post::class, 'product', 'id');
    }

    public function parentCmt($id){
        $data = Comment::where('id', $id)->where('is_active', 1)->whereNull('deleted_at')->first();
        return $data ?? null;
    }

    public function getRepcomnentByCommentID(){
        // $data = DB::table('rep_comments')
        //         ->join('comments','rep_comments.id_comment','comments.id')
        //         ->where('rep_comments.id_comment',$idComment);
         return $this->hasMany(Rep_comment::class, 'id_comment', 'id');
    }


    public function repComment(){
        return $this->hasOne(Rep_comment::class, 'id_comment', 'id');
    }

}
