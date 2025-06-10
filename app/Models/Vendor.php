<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Model
{
    use HasApiTokens, Notifiable;
    protected $fillable = [
        'business_name',
        'business_address',
        'home_address',
        'email',
        'street',
        'city',
        'state',
        'country',
        'phone',
        'password',
        'profile_image',
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
