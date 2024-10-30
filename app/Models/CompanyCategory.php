<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCategory extends Model
{
    use HasFactory;
    protected $table = 'company_category';
    protected $fillable = [
        'cate_id',
        'company_id',
        'description'
    ];
    public function companies()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'cate_id');
    }
}
