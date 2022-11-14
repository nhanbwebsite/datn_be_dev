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
        "url_img",
        "is_active" ,
        "created_by" ,
        "updated_by",
        "deleted_by",
        "created_at" ,
        "updated_at",
        "deleted_at" ,
    ];

    public function subcategory(){
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }
}
