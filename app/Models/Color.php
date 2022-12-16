<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'colors';
    protected $fillable = [
        "name",
        "slug" ,
        "color_code",
        "is_active" ,
        "created_by" ,
        "updated_by",
        "deleted_by",
        "created_at" ,
        "updated_at",
        "deleted_at" ,
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }


}
