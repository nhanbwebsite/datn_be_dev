<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Branch extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'branch';
    protected $fillable = [
        'branch_name',
        'slug',
        'deleted_by',
        'brand_id',
        'branch_id',
        'subcategory_id',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

}
