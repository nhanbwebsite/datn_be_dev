<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImportSlipDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'product_import_slip_details';
    protected $fillable = [
        'product_import_slip_id',
        'product_id',
        'pro_variant_id',
        'color_id',
        'quantity_import',
        'price_import',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
