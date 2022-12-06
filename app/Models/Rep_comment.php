<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rep_comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rep_comments';
    protected $fillable = [
        "id_comment",
        "rep_comment",
        "is_active",
        "created_at" ,
        "updated_at",
        "created_by" ,
        "updated_by",
        "deleted_at" ,
        "deleted_by",
    ];

}
