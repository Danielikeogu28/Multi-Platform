<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategories extends Model
{
    protected $fillable = [
        'name',
        'has_custom_fields',
    ];

    protected $casts = [
        'has_custom_fields' => 'boolean',
    ];
}
