<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CollectionImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'collectionImages';
    protected $fillable = [
        "id",
        "variant_deatils_id" ,
        'url_image_variant_details',
        "is_active" ,
        "created_by" ,
        "updated_by",
        "deleted_by",
        "created_at" ,
        "updated_at",
        "deleted_at" ,
    ];
}
