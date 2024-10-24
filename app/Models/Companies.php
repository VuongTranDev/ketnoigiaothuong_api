<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;
    public function companyCategory(){
        return $this->hasMany(CompanyCategory::class,'company_id');
    }
    public function addresses(){
        return $this->hasMany(Address::class,'company_id');
    }
    public function companyImage(){
        return $this->hasMany(CompanyImage::class,'company_id');
    }
}
