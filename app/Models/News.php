<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    public function categories(){
        return $this->belongsTo(Categories::class,'cate_id');
    }
    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function comments(){
        return $this->hasMany(Comments::class,'new_id');
    }
}
