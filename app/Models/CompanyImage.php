<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyImage extends Model
{
    use HasFactory;
    public function companies(){
        return $this->belongsTo(Companies::class,'company_id');
    }
}
