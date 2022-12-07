<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sms_requests';
    protected $fillable = [
        'phone',
        'code',
        'code_expired',
        'is_used',
        'created_at',
        'updated_at',
        'deleted_at',
        'deleted_by',
    ];
}
