<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    public function companyCategory(){
        return $this->hasMany(CompanyCategory::class,'cate_id');
    }
    public function news(){
        return $this->hasMany(News::class,'cate_id');
    }
    
}
