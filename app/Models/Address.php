<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'details',
        'address',
        'company_id'
    ];
    public function companies()
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }
}
