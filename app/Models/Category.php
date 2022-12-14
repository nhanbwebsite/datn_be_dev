<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SubCategory;
class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categories';
    protected $fillable = [
        "name",
        "slug" ,
        'is_post',
        "url_img",
        "is_active" ,
        "created_by" ,
        "updated_by",
        "deleted_by",
        "created_at" ,
        "updated_at",
        "deleted_at" ,
    ];

    public function subs(){
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }

    public static function subByCategoryID($category_id){
        $data = SubCategory::where('category_id',$category_id)->get();
        return $data;
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function slideshowBycate(){
        return $this->hasMany(Slideshow::class, 'category_id', 'id');
    }

}
