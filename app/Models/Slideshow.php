<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Slideshow extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'slideshow';
    protected $fillable = [
        'id',
        'title',
        'slug',
        'category_id',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details(){
        return $this->hasMany(Slideshow_detail::class, 'slideshow_id', 'id');
    }
    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public static function getDetailById($id){
        $data = Slideshow_detail::where('slideshow_id', $id)->get();
         return $data;
    }
}
