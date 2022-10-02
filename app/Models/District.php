<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $fillable = [
        'province_id',
        'name',
    ];

    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }
}
