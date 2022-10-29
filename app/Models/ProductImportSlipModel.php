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
        'user_id',
        'product_id',
        'warehouse_id',
        'product_amount',
        'delete_by',
        'update_by',
        'created_at',
        'deleted_at',
        'created_by',
        'updated_at',
    ];
}
