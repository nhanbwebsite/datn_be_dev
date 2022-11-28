<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGuide extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'payment_guide';
    protected $fillable = [
        'title',
        'content',
        'slug',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
