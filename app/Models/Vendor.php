<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Authenticatable
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
        'vendor_category_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function category()
    {
        return $this->belongsTo(VendorCategories::class, 'vendor_category_id', 'id');
    }
   
}
