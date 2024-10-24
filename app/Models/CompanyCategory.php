<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCategory extends Model
{
    use HasFactory;

    public function companies(){
        return $this->belongsTo(Companies::class,'company_id');
    }

    public function categories(){
        return $this->belongsTo(Categories::class,'category_id');
    }
    





}
