<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Rep_comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rep_comments';
    protected $fillable = [
        "id_comment",
        "rep_comment",
        "is_active",
        "created_at" ,
        "updated_at",
        "created_by" ,
        "updated_by",
        "deleted_at" ,
        "deleted_by",
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public static function getUserName($userId){
        $data = DB::table('users')
                ->where('users.id',$userId)
                ->first()->name;
        return $data;
    }

}
