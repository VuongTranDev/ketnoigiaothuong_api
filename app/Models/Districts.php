<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    use HasFactory;
    public function provinces(){
        return $this->belongsTo(Provinces::class,'province_id');
    }
    public function wards(){
        return $this->hasMany(Wards::class,'district_id');
    }
}
