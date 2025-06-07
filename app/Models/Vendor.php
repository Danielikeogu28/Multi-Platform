<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'business_name',
        'business_address',
        'email',
        'phone',
        'password',
        'category_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
    
}
