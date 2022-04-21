<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'state',
        'type',
        'bedrooms',
        'address',
        'price_per_anum',
        'image',
        'upload_successful',
        'disk'
    ];
}
