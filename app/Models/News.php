<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'title',
        'tag_name',
        'content',
        'cate_id',
        'user_id',
    ];
    public function categories(){
        return $this->belongsTo(Categories::class,'cate_id');
    }
    public function users(){
        return $this->belongsTo(Users::class,'user_id');
    }
    public function comments(){
        return $this->hasMany(Comments::class,'new_id');
    }
}
