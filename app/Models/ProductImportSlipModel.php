<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductImportSlipModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_import_slip';
    protected $fillable = [
        'name',
        'slug',
        'product_id',
        'store_id',
        'product_amount',
        'import_price',
        'delete_by',
        'update_by',
        'created_at',
        'deleted_at',
        'create_by',
        'updated_at',
    ];
}
