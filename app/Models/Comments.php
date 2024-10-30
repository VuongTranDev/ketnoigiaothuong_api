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
    public function users(){
        return $this->belongsTo(Users::class,'user_id','id');
    }

    protected $fillable = [
        'content', // Đảm bảo rằng tên cột này chính xác
        'user_id',
        'new_id',
    ];
}
