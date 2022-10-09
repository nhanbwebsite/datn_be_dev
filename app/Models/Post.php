<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_post_category','id_user','title','short_des','content_post','image','created_by','updated_by','deleted_by'
    ];
    protected $table = 'post';
}
