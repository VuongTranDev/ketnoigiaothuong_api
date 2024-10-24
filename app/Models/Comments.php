<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    public function news(){
        return $this->belongsTo(News::class,'new_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
