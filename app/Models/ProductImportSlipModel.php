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
        'code',
        'warehouse_id',
        'status',
        'note',
        'deleted_by',
        'updated_by',
        'created_at',
        'deleted_at',
        'created_by',
        'updated_at',
    ];

    public function details(){
        return $this->hasMany(ProductImportSlipDetail::class, 'product_import_slip_id', 'id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
